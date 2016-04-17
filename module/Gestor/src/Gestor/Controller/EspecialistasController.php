<?php
namespace Gestor\Controller;

use Gestor\Form\SustituirEspecialistaForm;
use Zend\View\Model\ViewModel;

use Gestor\Model\EspecialistaTable;  // Para poder acceder a los objetos del modelo
use Gestor\Form\EspecialistaForm;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;

class EspecialistasController extends AuthController implements ControllerInterface
{
    protected $datatable;
    protected $especialistaTable;
    protected $relacionaEquipoEspecialidadTable;
    protected $especialidadTable;

    public function setControllerVars()
    {
        $this->especialistaTable = $this->sm->get('Gestor\Model\EspecialistaTable');
        $this->especialidadTable = $this->sm->get('Gestor\Model\EspecialidadTable');
        $this->relacionaEquipoEspecialidadTable = $this->sm->get('Gestor\Model\RelacionaEquipoEspecialidadTable');
        $this->datatable = $this->sm->get('Gestor\Service\DatatableService');
        $this->formService      = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->especialistaTable);
    }

    private function setDatatableConfig()
    {
        $dataTableConfig = $this->especialistaTable->getDatatableConfig();

        $disallowSearchTo = array (
            'especialista.id'                         => false,
            'especialista.foto'                       => false
        );

        $disallowOrderTo = $disallowSearchTo;

        $dataTableConfig += array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) {
                //ocultamos la columna ID
                $header['especialista.id']['options']['visible']            = false;

                //Renombramos las columnas para darles un nombre más descriptivo en el header de la tabla
                $header['especialista.nombre']['value']                     = 'Nombre y apellidos';
                $header['relaciona_equipo_especialidad.id_equipo']['value'] = 'Equipos asignados';
                $header['especialidad.nombre_interno']['value']             = 'Especialidad';

                // Ponemos mayúsculas a las columnas
                $header['especialista.foto']['value']                     = 'Foto';
                $header['especialista.activo']['value']                     = 'Activo';

                //Indicamos la columna por la que se ordenará la tabla en un principio
                $header['especialista.activo']['order'] = 'desc';

                //Añadimos las columnas que contendrán los iconos de edición y activar/desactivar
                $header['edit'] = array(
                    'value' => 'Editar',
                    'options' => array(
                        'orderable' => false,
                        'searchable' => false,
                    )
                );
                $header['delete'] = array(
                    'value' => 'Activar / Desactivar',
                    'options' => array(
                        'orderable' => false,
                        'searchable' => false,
                    )
                );

                $header['substitute'] = array(
                    'value' => 'Sustituir',
                    'options' => array(
                        'orderable' => false,
                        'searchable' => false,
                    )
                );

                return $header;
            },
            'parse_row_data'=> function ($row) {

                //$row contiene los datos de cada una de las filas que ha generado la consulta.
                //Desde aquí podemos parsear los datos antes de visualizarlos por pantalla

                if (($row['foto'] != '') && ($row['foto'] != null)) {
                    $imagen = "<img width='100' src='%s' />";
                    // Metemos la imagen para que se vea que existe
                    $path = '/upload/especialista/';
                    $pathCompleto = $path . $row['foto'];
                    $pathCompleto = preg_replace("/(.*)\.(png|gif|jpg|jpeg)$/", "$1_thumb.$2", $pathCompleto);

                    $row['foto'] = sprintf($imagen, $pathCompleto);
                } else {
                    $row['foto'] = "<span style='color: red;'>No existe imagen</span>";
                }

                $linkVerEquipo = " <span style='padding-left:3em;'></span><a class='js-ver-equipos' href='%s'>Ver equipos</a>";
                $linkVerDirectores = " <span style='padding-left:3em;'></span><a class='js-ver-directores' href='%s'>Ver directores</a>";

                $linkVerEquipo = sprintf($linkVerEquipo, $this->goToSection('especialistas',array('action' => 'equipos', 'id' => $row['id']),true));
                $linkVerDirectores = sprintf($linkVerDirectores, $this->goToSection('especialistas',array('action' => 'directores', 'id' => $row['id']),true));

                $row['id_equipo'] .= $linkVerEquipo;
                $row['Directores_asignados'] .= $linkVerDirectores;

                //Insertamos los links en las columnas de edicion y activación.

                $link = "<a href='%s'><i class='fa %s fuentegrande'></i></a>";

                $deleteUrlParams = array('action' => 'change-status');

                $classIcon = $row['activo'] ? 'desactivar fa-check-square-o fuentegrande' : 'activar fa-square-o fuentegrande';
                $deleteUrlParams['id'] = $row['id'];

                $editUrl = $this->goToSection('especialistas',array('action' => 'edit', 'id' => $row['id']),true);
                $deleteUrl = $this->goToSection('especialistas',$deleteUrlParams,true);
                $substituteUrl = $this->goToSection('especialistas',array('action' => 'substitute', 'id' => $row['id']),true);

                $row['edit'] = sprintf($link,$editUrl, 'fa-edit');
                $row['delete'] = sprintf($link,$deleteUrl, $classIcon);
                $row['substitute'] = sprintf($link,$substituteUrl, 'fa-exchange');

                $row['activo'] = $row['activo'] == '1' ? 'SI' : 'NO';

                return $row;
            }
        );

        $this->datatable->setConfig($dataTableConfig);
    }

    public function exportAction()
    {
        $this->setDatatableConfig();

        $headers = $this->datatable->getHeaderFields();

        unset($headers['edit']);
        unset($headers['delete']);

        $view = new ViewModel(array(
            'results' => $this->datatable->runLastQuery(true)->toArray(),
            'headers' => $headers
        ));

        $view->setTemplate('gestor/export-datatable')->setTerminal(true);

        $routeMatch = $this->sm->get('Application')->getMvcEvent()->getRouteMatch();
        $section = $routeMatch->getParam('section');

        $output = $this->getServiceLocator()
            ->get('viewrenderer')
            ->render($view);

        $response = $this->getResponse();

        $headers = $response->getHeaders();

        $date = date('Y-m-d_H_i');

        $filename = $section . "_" . $date . ".xls";


        $headers->addHeaderLine('Content-Type', 'application/ms-excel')
            ->addHeaderLine(

                'Content-Disposition',
                sprintf("attachment; filename=\"%s\"", $filename)
            )
            ->addHeaderLine('Accept-Ranges', 'bytes')
            ->addHeaderLine('Content-Length', strlen($output));

        $response->setContent($output);

        return $response;
    }

    public function changeStatusAction()
    {
        $params = $this->params();
        $especialistaId = $params->fromRoute('id');
        $newStatus = $params->fromPost('newStatus');

        $resultSet = $this->relacionaEquipoEspecialidadTable->select(array(
            'id_especialista' => $especialistaId
        ));

        $error = '';
        $status = 'ok';

        if ($newStatus == 1 or $resultSet->count() == 0) {
            $this->especialistaTable->save(array(
                'activo' => $newStatus
            ), $especialistaId);
            //Cambiamos el estado del especialista
        } else {
            //No podemos desactivamos el especialista porque está asignado a un equipo
            $status = 'ko';
            $error = "No se puede desactivar un especialista mientras esté asignado a un equipo";
        }

        $result = array(
            'status' => $status,
            'error' => $error
        );

        echo json_encode($result);
        die;
    }

    public function indexAction()
    {
        $this->setDatatableConfig();

        if ($this->getRequest()->isPost()) {
            $result = $this->datatable->getData();
            $this->response->setContent(json_encode($result));
            return $this->response;
        } else {

            $settings = array(
                'headers' => $this->datatable->getHeaderFields(),
                'dropdown_filters' => array(
                    'especialidad.nombre_interno' => $this->especialidadTable
                        ->select()
                        ->toKeyValueArray('nombreInterno','nombreInterno'),
                    'especialista.activo' => array(
                        '0' => 'NO',
                        '1' => 'SI'
                    )
                )
            );

            $viewModel = new ViewModel(array(
                'settings'   => $settings,
                'table_id'   => 'especialistaTable',
                'add_action' => $this->goToSection('especialistas',array('action'=>'add'),true),
                'export_action' => $this->goToSection('especialistas',array('action'=>'export'),true),
                'title'      => 'Listado de especialistas'
            ));
            $viewModel->setTemplate('gestor/list-datatable');
            return $viewModel;
        }
    }

    public function fotoAction()
    {
        $id = $this->params()->fromRoute('id');

        $especialistaEditar = $this->especialistaTable->getEspecialista($id);

        $foto = '';

        if ($especialistaEditar->foto != '') {
            $foto = '/upload/especialista/' . $especialistaEditar->foto;
            $foto = preg_replace("/(.*)\.(png|gif|jpg|jpeg)$/", "$1_thumb.$2", $foto);
        }

        echo json_encode(array(
            'status' => 'ok',
            'result' => $foto
        ));
        die;
    }

    public function equiposAction()
    {

    }

    public function directoresAction()
    {

    }


    public function addAction()
    {
        $especialistaForm = new EspecialistaForm();
        $especialistaForm->setServiceLocator($this->getServiceLocator());
        $especialistaForm->addFields();

        $this->formService->setForm($especialistaForm);

        $form = $this->formService->getForm();
        //$form->bind($especialista);
        $form->get('submit')->setAttribute('value', 'Añadir');

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();

        if ($request->isPost()) {

            $especialista = $this->especialistaTable->getEntityModel();

            $post = $request->getPost();
            $form->setInputFilter($especialista->getInputFilter());
            $form->bind($post);


            if ($form->isValid()) {
                // Metemos los datos que vamos a guardar
                $especialista->exchangeArray($post);
                $insertId = $this->especialistaTable->saveEspecialista($especialista); // Grabamos lo que teníamos bindeado al formatho ("XXX");

                // Ahora vamos a crear nosotros el avatar
                $config = $this->sm->get('Config');

                $pathUpload = $config['pathbase_upload'].$config['path_especialista'];
                // Llamamos al servicio que nos va a grabar el fichero y a montar el thumbnail
                $nombreNuevoFichero = $this->sm->get('Gestor\Service\ImageUploadService')->UploadImage($pathUpload,
                    $config['maxWidthEspecialista'], $config['maxHeightEspecialista'],
                    $_FILES['foto'], $insertId);

                // Ahora llamamos a la base de datos pasándole el nombre del campo, $nombreNuevoFichero, para nuestro ID
                $this->especialistaTable->saveFoto($insertId, $nombreNuevoFichero);

                return $this->goToSection('especialistas',array(
                    'action' => 'edit',
                    'id' => $insertId
                ));
            }

        }

        //$form = $this->formService->getForm();

        return new ViewModel(array(
            //'id'          => $id,
            'form'        => $form
        ));
    }

    public function substituteAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->goToSection('especialistas');
        }


            $sustituirEspecialistaForm = new SustituirEspecialistaForm();
            $sustituirEspecialistaForm->setServiceLocator($this->getServiceLocator());
            //Recogemos los equipos que tiene el especialista a sustituir
            $equiposEspecialista = $this->relacionaEquipoEspecialidadTable->fetchFromEspecialista($id)->toArray();

        //Recogemos el especialista que vamos a sustituir
            $especialistaEditar = $this->especialistaTable->getEspecialista($id);

            $sustituirEspecialistaForm->addFields($id,$especialistaEditar,$equiposEspecialista);
        try {
            $this->formService->setForm($sustituirEspecialistaForm);

            $form = $this->formService->getForm();

            $form->get('submit')->setAttribute('value', 'Sustituir');

            // Si es un request tipo post grabamos los datos y redirigimos
            $request = $this->getRequest();

            if ($request->isPost()) {
                $postParams = $this->params()->fromPost();

                foreach ($postParams as $postField => $postValue) {
                    if (strpos($postField, 'idespecialista_') !== false) {
                        //Coge el último número de la cadena, justo el que hay después del guión bajo
                        $relacionId = preg_replace("/(.*_)(\d+)$/", "$2", $postField);

                        $this->relacionaEquipoEspecialidadTable->update(
                            array(
                                'id_especialista' => $postValue
                            ),
                            //WHERE
                            array(
                                'id' => $relacionId
                            )
                        );
                    }
                }

                return $this->goToSection('especialistas',array('action' => 'substitute', 'id' => $id));
            }

            //agrupamos por director de relación los equipos en los que está el especialista

            $groupByDirector = array();

            foreach ($equiposEspecialista as $equipo) {
                if (!isset($groupByDirector[$equipo['idDirector']])) {
                    $groupByDirector[$equipo['idDirector']] = array(
                        'director' => $equipo['director'],
                        'equipos' => array()
                    );
                }

                $groupByDirector[$equipo['idDirector']]['equipos'][] = $equipo;
            }

            $equiposEspecialista = $groupByDirector;

            return new ViewModel(compact(
                'especialistaEditar',
                'equiposEspecialista',
                'form'
            ));
        } catch (\Exception $ex) {
            return $this->goToSection('especialistas');
        }
    }

    public function editAction()
    {
        // Vamos a sacar/grabar los datos
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('especialistas');
        }

        // Sacamos los datos de una entrada en concreto
        try {
            $especialista = $this->especialistaTable->getEspecialista($id);
        } catch (\Exception $ex) {
            return $this->goToSection('especialistas');
        }

        // Vamos a sacar en cuantos grupos está. Si es > 0, no dejaremos editar el tipo
        $equiposEspecialista = $this->relacionaEquipoEspecialidadTable->fetchFromEspecialista($id)->toArray();

        // Esto lo sacamos por nombre porque si
        $miEspecialidad = $this->especialidadTable->getEspecialidad($especialista->idespecialidad);

        // Le bindeamos los datos al formulario y configuramos para que el submit ponga Edit
        //$this->formService->SetServiceLocator($this->sm);
        //$this->formService->setForm(new EspecialistaForm())->addFields();
        $especialistaForm = new EspecialistaForm();
        $especialistaForm->setServiceLocator($this->getServiceLocator());
        $especialistaForm->addFields();

        $this->formService->setForm($especialistaForm);

        $form = $this->formService->getForm();
        $form->bind($especialista);
        $form->get('submit')->setAttribute('value', 'Grabar');

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();

        //$this->formService->setForm(new EspecialistaForm())->addFields();
        //$form = $this->formService->getForm();

//die;
        // Le bindeamos los datos al formulario
        $form->bind($especialista);

        if ($request->isPost()) {

            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
//var_dump($_FILES);
//die;
            //$post = $request->getPost();

            //$especialista->tieneEnlace = (int) ($post['idControlador'] != null and $post['idControlador'] != '');

            $form->setInputFilter($especialista->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {


                // Grabamos lo que teníamos bindeado al form - me aseguro 20mil veces pq como esto no rule la liamos gorda
                $this->especialistaTable->saveEspecialista($especialista);


                // SOLO si se ha subido una nueva foto

                // Si había una foto antigua para este especialista, tenemos que borrarla!
                $especialistaOld = $this->especialistaTable->getEspecialista($especialista->id);
                $fotoBorrar = $especialistaOld->foto;

                if (($_FILES['foto']['name'] != '') && ($_FILES['foto']['name'] != null))
                {
                    // Ahora vamos a crear nosotros el avatar
                    $config = $this->sm->get('Config');
                    $pathUpload = $config['pathbase_upload'].$config['path_especialista'];

                    // Llamamos al servicio que nos va a grabar el fichero y a montar el thumbnail
                    $nombreNuevoFichero = $this->sm->get('Gestor\Service\ImageUploadService')->UploadImage($pathUpload,
                        $config['maxWidthEspecialista'], $config['maxHeightEspecialista'],
                        $_FILES['foto'], $post['id']);

                    // Ahora llamamos a la base de datos pasándole el nombre del campo, $nombreNuevoFichero, para nuestro ID
                    if ($nombreNuevoFichero != -1)
                    {
                        $this->especialistaTable->saveFoto($post['id'], $nombreNuevoFichero);

                        // Vamos a borrar la anterior, si la hay
                        if (($especialista->foto != '') && ($especialista->foto != null))
                        {
                            $nombreBorrar = $pathUpload.$fotoBorrar; //$especialista->foto;
                            $nombreBorrarThumb = substr($nombreBorrar, 0, strlen($nombreBorrar) - 4)."_thumb".substr($nombreBorrar, (strlen($nombreBorrar) - 4), strlen($nombreBorrar));
                            unlink($nombreBorrar);
                            unlink($nombreBorrarThumb);
                        }
                    }

                }
//die;

                return $this->goToSection('especialistas',array(
                    'action' => 'edit',
                    'id' => $id
                ));
            }

        }

        return new ViewModel(array(
            'id'          => $id,
            'form'        => $form,
            'equiposEspecialista'  => $equiposEspecialista,
            'miEspecialidad' => $miEspecialidad,
        ));
    }


    // *********************** Borrado de un especialista ***********************

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id != 0) {
            // Vamos a sacar los datos para ver si hay alguna foto
            $especialista = $this->especialistaTable->getEspecialista($id);

            if (($especialista->foto != '') && ($especialista->foto != null))
            {
                // Borramos la foto en el disco duro
                $config = $this->sm->get('Config');
                $pathUpload = $config['pathbase_upload'].$config['path_especialista'];
                $nombreBorrar = $pathUpload.$especialista->foto;
                $nombreBorrarThumb = substr($nombreBorrar, 0, strlen($nombreBorrar) - 4)."_thumb".substr($nombreBorrar, (strlen($nombreBorrar) - 4), strlen($nombreBorrar));
                unlink($nombreBorrar);
                unlink($nombreBorrarThumb);
            }

            // Borramos con este ID
            $this->especialistaTable->deleteEspecialista($id);
        }

        // Nos vamos al listado general
        return $this->goToSection('especialistas');
    }
}