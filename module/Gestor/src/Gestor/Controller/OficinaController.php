<?php
namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;
use Gestor\Form\OficinaForm;



class OficinaController extends AuthController implements ControllerInterface
{
    protected $oficinaTable;

    public function setControllerVars()
    {
        $this->oficinaTable = $this->sm->get('Gestor\Model\OficinaTable');
        $this->directorRelacionTable = $this->sm->get('Gestor\Model\DirectorRelacionTable');
        $this->datatable = $this->sm->get('Gestor\Service\DatatableService');
        $this->formService      = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->oficinaTable);
    }


    public function activarAction()
    {

        // Afectamos a lo que recibimos como $id
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('oficina');
        }

        $this->oficinaTable->activarDesactivar($id, 1);

        return $this->goToSection('oficina',array(
            'action' => 'index'
        ));

    }

    public function desactivarAction()
    {

        // Afectamos a lo que recibimos como $id
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('oficina');
        }

        $this->oficinaTable->activarDesactivar($id, 0);

        return $this->goToSection('oficina',array(
            'action' => 'index'
        ));

    }

    private function setDatatableConfig()
    {
        $dataTableConfig = $this->oficinaTable->getDatatableConfig();

        $disallowSearchTo = array ();

        $disallowOrderTo = $disallowSearchTo;

        $dataTableConfig += array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) {
                //ocultamos la columna ID

                $header['provincia.nombre']['value'] = 'Provincia';
                $header['oficina.numerooficina']['value'] = 'Nº Oficina';

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

                return $header;
            },
            'parse_row_data'=> function ($row) {

                //$row contiene los datos de cada una de las filas que ha generado la consulta.
                //Desde aquí podemos parsear los datos antes de visualizarlos por pantalla


                //Insertamos los links en las columnas de edicion y activación.

                $link = "<a href='%s'><i class='fa %s fuentegrande'></i></a>";

                $deleteUrlParams = array('action' => 'change-status');

                $classIcon = $row['activa'] ? 'desactivar fa-check-square-o' : 'activar fa-square-o';
                $deleteUrlParams['id'] = $row['id'];

                $editUrl = $this->goToSection('oficina',array('action' => 'edit', 'id' => $row['id']),true);
                $deleteUrl = $this->goToSection('oficina',$deleteUrlParams,true);

                $row['edit'] = sprintf($link,$editUrl, 'fa-edit');
                $row['delete'] = sprintf($link,$deleteUrl, $classIcon);

                $row['activa'] = $row['activa'] == '1' ? 'SI' : 'NO';

                return $row;
            }
        );

        $this->datatable->setConfig($dataTableConfig);
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
                    'oficina.activa' => array(
                        '0' => 'NO',
                        '1' => 'SI'
                    )
                )
            );

            $viewModel = new ViewModel(array(
                'settings'   => $settings,
                'table_id'   => 'oficinaTable',
                'add_action' => $this->goToSection('oficina',array('action'=>'add'),true),
                'export_action' => $this->goToSection('oficina',array('action'=>'export'),true),
                'title'      => 'Listado de oficinas'
            ));
            $viewModel->setTemplate('gestor/list-datatable');
            return $viewModel;
        }
    }

    public function changeStatusAction()
    {
        $params = $this->params();
        $oficinaId = $params->fromRoute('id');
        $newStatus = $params->fromPost('newStatus');

        $resultSet = $this->directorRelacionTable->select(array(
            'idoficina' => $oficinaId
        ));

        $error = '';
        $status = 'ok';

        if ($newStatus == 1 or $resultSet->count() == 0) {
            $this->oficinaTable->save(array(
                'activa' => $newStatus
            ), $oficinaId);
        } else {
            $status = 'ko';
            $error = "No se puede desactivar una oficina mientras tenga asignado un director de relación";
        }

        $result = array(
            'status' => $status,
            'error' => $error
        );

        echo json_encode($result);
        die;
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

    public function editAction()
    {

        // Vamos a sacar/grabar los datos
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('oficina');
        }

        // Sacamos los datos de una entrada en concreto
        try {
            $oficina = $this->oficinaTable->getOficina($id);
        } catch (\Exception $ex) {
            return $this->goToSection('oficina');
        }

//var_dump($oficina); die;

        $oficinaForm = new OficinaForm();
        $oficinaForm->setServiceLocator($this->getServiceLocator());
        $oficinaForm->addFields();


        $this->formService->setForm($oficinaForm);

        $form = $this->formService->getForm();
        $form->bind($oficina);
        $form->get('submit')->setAttribute('value', 'Editar');

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();

        // Le bindeamos los datos al formulario
        $form->bind($oficina);

        if ($request->isPost()) {
            // alterar
            $post = $request->getPost()->toArray();
            $form->setInputFilter($oficina->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {
                // Grabamos lo que teníamos bindeado al form
                $this->oficinaTable->saveElemento($oficina);
            }

            return $this->goToSection('oficina',array(
                'action' => 'edit',
                'id' => $id
            ));
        }

        return new ViewModel(array(
            'id'          => $id,
            'form'        => $form,
            'oficina'     => $oficina,
        ));

    }




    public function addAction()
    {
        $oficinaForm = new OficinaForm();
        $oficinaForm->setServiceLocator($this->getServiceLocator());
        $oficinaForm->addFields();

        $this->formService->setForm($oficinaForm);

        $form = $this->formService->getForm();
        //$form->bind($especialista);
        $form->get('submit')->setAttribute('value', 'Añadir');

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();

        if ($request->isPost()) {
            $oficina = $this->oficinaTable->getEntityModel();

            $post = $request->getPost();
            $form->setInputFilter($oficina->getInputFilter());
            $form->bind($post);


            if ($form->isValid()) {

                // Metemos los datos que vamos a guardar
                $oficina->exchangeArray($post);
                $insertId = $this->oficinaTable->saveElemento($oficina); // Grabamos lo que teníamos bindeado al formatho ("XXX");

                // Nos vamos al editar
                return $this->goToSection('oficina',array(
                    'action' => 'edit',
                    'id' => $insertId
                ));
            }
        }

        return new ViewModel(array(
            'form'        => $form
        ));
    }



}

