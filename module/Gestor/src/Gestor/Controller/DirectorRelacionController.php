<?php
namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

use Gestor\Model\DirectorRelacionTable;  // Para poder acceder a los objetos del modelo
use Gestor\Model\GestorUsuariosTable;
use Gestor\Form\DirectorRelacionForm;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;


class DirectorRelacionController extends AuthController implements ControllerInterface
{
    protected $directorrelacionTable;
    protected $gestorusuariosTable;
    protected $especialidadTable;
    protected $especialistaTable;
    protected $relacionaequipoespecialidadTable;
    protected $equipoTable;
    protected $ultimosdatosTable;


    public function setControllerVars()
    {
        $this->directorrelacionTable = $this->sm->get('Gestor\Model\DirectorRelacionTable');
        $this->gestorusuariosTable = $this->sm->get('Gestor\Model\GestorUsuariosTable');
        $this->especialistaTable = $this->sm->get('Gestor\Model\EspecialistaTable');
        $this->especialidadTable = $this->sm->get('Gestor\Model\EspecialidadTable');
        $this->equipoTable = $this->sm->get('Gestor\Model\EquipoTable');
        $this->relacionaequipoespecialidadTable = $this->sm->get('Gestor\Model\RelacionaEquipoEspecialidadTable');
        $this->ultimosdatosTable = $this->sm->get('Gestor\Model\UltimosDatosOficinaTable');

        $this->formService      = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->directorrelacionTable);
        $this->datatable = $this->sm->get('Gestor\Service\DatatableService');
    }



    // ************************************************************
    //               Accion de crear un equipo
    // ************************************************************

    public function crearequiposAction()
    {
        // Vamos a sacar/grabar los datos
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('director_relacion');
        }

        // Sacamos los datos de una entrada en concreto
        try {
            $encryptKey = $this->config['encrypt_key'];
            $directorRelacion = $this->directorrelacionTable->getDirectorRelacion($id, $encryptKey);
        } catch (\Exception $ex) {
            return $this->goToSection('director_relacion');
        }

        $hayErrorSeleccione = 0; // Esto para la comprobación posterior de la seleccion si hay post

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();

        if ($request->isPost()) {

            // Añadimos el equipo si hay POST
            $post = $request->getPost();

            // Primer recorrido de los índices, para asegurarnos de que no valen = 0 (se ha elegido para todos ellos)
            foreach($_POST as $key => $unParametro) {
                if (strpos($key, 'especialidad') !== false) {
                    if ($unParametro == 0) {
                        $hayErrorSeleccione = 1;
                    }
                }
            }
            if ($hayErrorSeleccione == 0) {

                // Continuamos solo si no hay error
                
                // Creamos un equipo nuevo y nos quedamos su id
                $idNuevoEquipo = $this->equipoTable->CrearEquipo($id);

                // Vamos a recorrer todos los indices y a grabar
                foreach($_POST as $key => $unParametro) {

                    if (strpos($key, 'especialidad') !== false)
                    {
                        $idEspecialidadKey = substr($key,12);
                        //  Ahora tenemos ese $idEspecialidadKey indicando el id de especialidad
                        // y también tenemos un $unParametro con el id del especialista. En cada parte interna de aqui,
                        // vamos a tener que hacer una entrada en la tabla relaciona_equipo_especialidad, cuyos parametros
                        // son (id, id_equipo, id_especialista).

                        //  En la práctica esto es más sencillo de lo que parece entonces, solo el insert con el id_equipo
                        // que habíamos sacado antes, y el $unParametro como id_especialista.
                        $idRelacionEsp = $this->relacionaequipoespecialidadTable->Crear($idNuevoEquipo, $unParametro);

                    }

                }

                //  TODO comprobar dinámicamente que se ha grabado todo lo que se tenia que grabar, y e.o.c. borrar las entradas
                // TODO en equipo y relaciones INDICANDO EL ERROR

                // Metemos un mensajito de que hemos hecho el ADD
                $this->flashMessenger()->addMessage('ADDTEAMOK');


                // Nos vamos al listado de equipos una vez introducido
                return $this->goToSection('director_relacion',array(
                    'action' => 'equipos',
                    'id' => $id
                ));


            }



        }


        // Sacamos la lista de especialidades
        $arrayEspecialidades = $this->especialidadTable->fetchAll();

        foreach ($arrayEspecialidades as  $i => $especialidad) :

            // Guardamos en un array su nombre
            $idEspecialidad = $especialidad->id;
            //$arrayNombresEspecialidad[$idEspecialidad] = $especialidad->id;
            $arrayNombresEspecialidad[$idEspecialidad]['nombreInterno'] = $especialidad->nombreInterno;
            $arrayNombresEspecialidad[$idEspecialidad]['id'] = $especialidad->id;

            // Para cada especialidad, sacamos los especialistas disponibles
            $espArray = $this->especialistaTable->fetchFromEspecialidad($idEspecialidad);

            foreach ($espArray as $n => $especialista) :
                $arrayEspecialistas[$idEspecialidad][$n] = $especialista;
            endforeach;
        endforeach;

        // Comprobamos si existe ya algún equipo o no y lo indicamos en rojo si no es así.
        $arrayEquipos = $this->equipoTable->fetchFromDirectorRelacion($id);
        $cuantosEquipos = $arrayEquipos->count();


        return new ViewModel(array(
            'directorRelacion' => $directorRelacion,
            'arrayNombresEspecialidad' => $arrayNombresEspecialidad,
            'arrayEspecialistas' => $arrayEspecialistas,
            'cuantosEquipos' => $cuantosEquipos,
            'hayErrorSeleccione' => $hayErrorSeleccione,
        ));
    }



    public function equiposAction()
    {
        // Vamos a sacar/grabar los datos
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('director_relacion');
        }

        // Sacamos los datos de una entrada en concreto
        try {
            $encryptKey = $this->config['encrypt_key'];
            $directorRelacion = $this->directorrelacionTable->getDirectorRelacion($id, $encryptKey);
        } catch (\Exception $ex) {
            return $this->goToSection('director_relacion');
        }

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();


        // Vamos a ver si tenemos mensaje de ADDOK
        $addTeamOk = 0; // Por defecto no lo pintamos
        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            $arrayFlash = $flashMessenger->getMessages();
            for ($m = 0; $m < count($arrayFlash); $m++)
                if($arrayFlash[$m] == 'ADDTEAMOK') // Si encontramos nuestro mensaje de ADDOK vamos a pasarlo
                {
                    $addTeamOK = 1;
                }
        }



        if ($request->isPost()) {

            // Añadimos el equipo si hay POST
            $post = $request->getPost();
            // Creamos un equipo nuevo y nos quedamos su id
            $idEquipo = $_POST['idequipo'];

//var_dump($_POST);
            // Vamos a recorrer todos los indices y a grabar
            foreach($_POST as $key => $unParametro) {

                if (strpos($key, 'especialidad') !== false)
                {
                    $idEspecialidadKey = substr($key,12);
                    $idRelacionEsp = $this->relacionaequipoespecialidadTable->Actualizar($idEquipo, $idEspecialidadKey, $unParametro);
                }
            }

            // Nos vamos al listado de equipos una vez introducido
            return $this->goToSection('director_relacion',array(
                'action' => 'equipos',
                'id' => $id
            ));

        }


        // Sacamos los equipos que están relacionados con este director_relacion
        $arrayEquipos = $this->equipoTable->fetchFromDirectorRelacion($id);
        $cuantosEquipos = $arrayEquipos->count();


        // Vamos a sacar los nombres de todas las especialidades
        $arrayEspecialidades = $this->especialidadTable->fetchAll(); // Por el campo "orden" ASC
        // Ahora para todas ellas
        foreach ($arrayEspecialidades as $i => $especialidad) :
            // Guardamos en un array su nombre
            $idEspecialidad = $especialidad->id;
            //$arrayNombresEspecialidad[$idEspecialidad] = $especialidad->id;
            $arrayNombresEspecialidad[$idEspecialidad]['nombreInterno'] = $especialidad->nombreInterno;
            $arrayNombresEspecialidad[$idEspecialidad]['id'] = $especialidad->id;

            // Tenemos que sacar todos los especialistas de cada cosa también, pera hacer la combo
            // Para cada especialidad, sacamos los especialistas disponibles
            $espArray = $this->especialistaTable->fetchFromEspecialidad($idEspecialidad);

            foreach ($espArray as $n => $especialista) :
                $arrayEspecialistas[$idEspecialidad][$n] = $especialista;
            endforeach;
        endforeach;



        // Ahora vamos a sacar los datos de cada uno de los especialistas de este equipo
        $contador = 0;
        foreach($arrayEquipos as $i => $equipo) {
            $arrayFinal[$contador]['id'] = $equipo->id;

            foreach($arrayNombresEspecialidad as $j => $especialidadParticular)
            {
                // El $j tiene el idEspecialidad
                $idEspecialidad = $j;
                // Ahora, sacamos quien es el especialista que está en este equipo y en esta especialidad
                $datosEspecialista = $this->relacionaequipoespecialidadTable->fetchFromEspecialidadEquipo($idEspecialidad, $equipo->id);
                // Los cargamos en la especialidad especifica
                $arrayFinal[$contador][$idEspecialidad]['nombre'] = $datosEspecialista->nombre;
                $arrayFinal[$contador][$idEspecialidad]['apellido1'] = $datosEspecialista->apellido1;
                $arrayFinal[$contador][$idEspecialidad]['apellido2'] = $datosEspecialista->apellido2;
                $arrayFinal[$contador][$idEspecialidad]['foto'] = $datosEspecialista->foto;
                $arrayFinal[$contador][$idEspecialidad]['idespecialista'] = $datosEspecialista->idEspecialista;
                //id_especialista

            }
            $contador++;
        }

        return new ViewModel(array(
            'directorRelacion' => $directorRelacion,
            'arrayEquipos' => $arrayFinal,
            'cuantosEquipos' => $cuantosEquipos,
            'arrayEspecialistas' => $arrayEspecialistas,
            'arrayNombresEspecialidad' => $arrayNombresEspecialidad,
            'addTeamOK' => $addTeamOK,
        ));
    }


    /*public function desactivarAction()
    {
        // Afectamos a lo que recibimos como $id
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('director_relacion');
        }

        // Obtenemos el id en la tabla de gestorusuarios
        try {
            $encryptKey = $this->config['encrypt_key'];
            $directorRelacion = $this->directorrelacionTable->getDirectorRelacion($id, $encryptKey);
        } catch (\Exception $ex) {
            return $this->goToSection('director_relacion');
        }
        $idUsuario = $directorRelacion->idusuario;

        $this->gestorusuariosTable->updateActivo($idUsuario, 0);

        return $this->goToSection('director_relacion',array(
            'action' => 'index'
        ));

    }*/

    /*public function activarAction()
    {
        // Afectamos a lo que recibimos como $id
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('director_relacion');
        }

        // Obtenemos el id en la tabla de gestorusuarios
        try {
            $encryptKey = $this->config['encrypt_key'];
            $directorRelacion = $this->directorrelacionTable->getDirectorRelacion($id, $encryptKey);
        } catch (\Exception $ex) {
            return $this->goToSection('director_relacion');
        }
        $idUsuario = $directorRelacion->idusuario;

        $this->gestorusuariosTable->updateActivo($idUsuario, 1);

        return $this->goToSection('director_relacion',array(
            'action' => 'index'
        ));

    }*/

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

    private function setDatatableConfig()
    {
        $dataTableConfig = $this->directorrelacionTable->getDatatableConfig();

        $disallowSearchTo = array (
            'gestorusuarios.ultimoLogin' => false
        );

        $disallowOrderTo = $disallowSearchTo;

        $dataTableConfig += array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) {

                //ocultamos la columna ID
                $header['director_relacion.id']['options']['visible']            = false;
                $header['oficina.datos_oficina']['options']['visible']            = false;

                $header['direccion_regional.nombre']['value']   = "Dirección Regional";
                $header['cargo.nombre_interno']['value']        = "Cargo";
                $header['gestorusuarios.login']['value']        = "Usuario";
                $header['oficina.numerooficina']['value']       = "Nº Oficina";
                $header['gestorusuarios.ultimoLogin']['value']  = "Último acceso";

                $header['gestorusuarios.activo']['value']                     = 'Activo';

                $header['equipo.equipos_asignados']['value']                     = 'Equipos asignados';
                $header['director_relacion.nombre_director']['value']      = 'Nombre director';
//var_dump($header); die;

                //Indicamos la columna por la que se ordenará la tabla en un principio
                $header['gestorusuarios.activo']['order'] = 'desc';

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

                $oficina = $row['numerooficina'] . " <a href='#' class='pull-right js-office-address-btn'>Ver Dirección</a><br/><span class='hide'>".$row['datos_oficina']."</span>";

                $row['numerooficina'] = $oficina;
                //Insertamos los links en las columnas de edicion y activación.

                $link = "<a href='%s'><i class='fa %s fuentegrande'></i></a>";

                $deleteUrlParams = array('action' => 'change-status');

                $classIcon = $row['activo'] ? 'desactivar fa-check-square-o' : 'activar fa-square-o';
                $deleteUrlParams['id'] = $row['id'];

                $editUrl = $this->goToSection('director_relacion',array('action' => 'edit', 'id' => $row['id']),true);
                $deleteUrl = $this->goToSection('director_relacion',$deleteUrlParams,true);

                $row['edit'] = sprintf($link,$editUrl, 'fa-edit');
                $row['delete'] = sprintf($link,$deleteUrl, $classIcon);

                $row['activo'] = $row['activo'] == '1' ? 'SI' : 'NO';

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
                    'gestorusuarios.activo' => array(
                        '0' => 'NO',
                        '1' => 'SI'
                    )
                )
            );

            $viewModel = new ViewModel(array(
                'settings'   => $settings,
                'table_id'   => 'directorRelacionTable',
                'add_action' => $this->goToSection('director_relacion',array('action'=>'add'),true),
                'export_action' => $this->goToSection('director_relacion',array('action'=>'export'),true),
                'title'      => 'Listado de directores de relación'
            ));
            $viewModel->setTemplate('gestor/list-datatable');
            return $viewModel;
        }
    }

    public function changeStatusAction()
    {
        $params = $this->params();
        $idDirectorRelacion = $params->fromRoute('id');
        $newStatus = $params->fromPost('newStatus');

        $encryptKey = $this->config['encrypt_key'];
        $directorRelacion = $this->directorrelacionTable->getDirectorRelacion($idDirectorRelacion, $encryptKey);

        $resultSet = $this->equipoTable->select(array(
            'iddirectorrelacion' => $idDirectorRelacion
        ));

        $error = '';
        $status = 'ok';

        $this->gestorusuariosTable->updateActivo($directorRelacion->idusuario, $newStatus);

        /*if ($newStatus == 1 or $resultSet->count() == 0) {
            //Cambiamos el estado

        } else {
            //No podemos desactivamos el especialista porque está asignado a un equipo
            $status = 'ko';
            $error = "No se puede desactivar un director de relación mientras tenga equipos a su cargo";
        }*/

        $result = array(
            'status' => $status,
            'error' => $error
        );

        echo json_encode($result);
        die;
    }

    public function addAction()
    {
        $directorrelacionForm = new DirectorRelacionForm();
        $directorrelacionForm->setServiceLocator($this->getServiceLocator());
        $directorrelacionForm->addFields(1); // El 1 indica que es un add, para meter el [Seleccione]

        $this->formService->setForm($directorrelacionForm);

        $form = $this->formService->getForm();
        //$form->bind($especialista);
        $form->get('submit')->setAttribute('value', 'Añadir');




        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();

        if ($request->isPost()) {
            $directorrelacion = $this->directorrelacionTable->getEntityModel();
            $gestorusuarios = $this->gestorusuariosTable->getEntityModel();

            $post = $request->getPost();
            $form->setInputFilter($directorrelacion->getInputFilter());
            $form->bind($post);

            // TODO vamos a comprobar si Cargo, DR u oficina valen 0
            $errores = 0;
            $arrayErrores = array();
            if ($_REQUEST['iddireccionregional'] == 0)
            {
                $errores = 1;
                $arrayErrores[] = "No ha elegido una Dirección Regional.";
            }
            if ($_REQUEST['idcargo'] == 0)
            {
                $errores = 1;
                $arrayErrores[] = "No ha elegido un cargo.";
            }
            if ($_REQUEST['idoficina'] == 0)
            {
                $errores = 1;
                $arrayErrores[] = "No ha elegido una oficina.";
            }
            if ($errores == 1) { // Si ha habido algún error, lo pintamos
                return new ViewModel(array(
                    'form'        => $form,
                    'arrayErrores'   => $arrayErrores,
                ));
            }


            if ($form->isValid()) {

                // Precargamos valores por defecto
                $post->password = $this->config['clavePorDefecto']; //"12345678";
                $post->idPerfil = 2;
                $post->validado = "0";

                $gestorusuarios->exchangeArray($post);
                $idUsuarioGestor = $this->gestorusuariosTable->saveGestorUsuarios($gestorusuarios);

                // Ahora vamos a grabar la clave bien
                $encryptKey = $this->config['encrypt_key'];
                $this->gestorusuariosTable->updatePassword($idUsuarioGestor,$post->password,$encryptKey);

                // Para guardar en la tabla de director el id de usuario
                $post->idusuario = $idUsuarioGestor;

                // Metemos los datos que vamos a guardar
                $directorrelacion->exchangeArray($post);
                $insertId = $this->directorrelacionTable->saveDirectorRelacion($directorrelacion); // Grabamos lo que teníamos bindeado al formatho ("XXX");

                // Grabamos aparte el email
                $this->directorrelacionTable->updateEmail($insertId, $directorrelacion->email, $encryptKey); //saveEmail($id, $encryptKey, $directorRelacion->email);


                // Metemos un mensajito de que hemos hecho el ADD
                $this->flashMessenger()->addMessage('ADDOK');

                // Nos vamos al editar
                return $this->goToSection('director_relacion',array(
                    'action' => 'edit',
                    'id' => $insertId
                ));
            }
        }

        return new ViewModel(array(
            'form'        => $form
        ));
    }



    public function editAction()
    {
        // Vamos a sacar/grabar los datos
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('director_relacion');
        }

        // Sacamos los datos de una entrada en concreto
        try {
            $encryptKey = $this->config['encrypt_key'];
            $directorRelacion = $this->directorrelacionTable->getDirectorRelacion($id, $encryptKey);
            $directorRelacion->email = $directorRelacion->emailDecrypted; // Apaño para que rule el form
            $idOficinaOld = $directorRelacion->idoficina;
        } catch (\Exception $ex) {
            return $this->goToSection('director_relacion');
        }

        // Vamos a ver si tenemos mensaje de ADDOK
        $addOk = 0; // Por defecto no lo pintamos
        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            $arrayFlash = $flashMessenger->getMessages();
            for ($m = 0; $m < count($arrayFlash); $m++)
            if($arrayFlash[$m] == 'ADDOK') // Si encontramos nuestro mensaje de ADDOK vamos a pasarlo
            {
                $addOK = 1;
            }
        }

        // Sacamos los equipos que están relacionados con este director_relacion
        $arrayEquipos = $this->equipoTable->fetchFromDirectorRelacion($id);
        $cuantosEquipos = $arrayEquipos->count();



        $directorrelacionForm = new DirectorRelacionForm();
        $directorrelacionForm->setServiceLocator($this->getServiceLocator());
        $directorrelacionForm->addFields();

        $this->formService->setForm($directorrelacionForm);

        $form = $this->formService->getForm();
        $form->bind($directorRelacion);
        $form->get('submit')->setAttribute('value', 'Guardar');

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();

        // Le bindeamos los datos al formulario
        $form->bind($directorRelacion);

        if ($request->isPost()) {

            $post = $request->getPost()->toArray();
            $form->setInputFilter($directorRelacion->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {

                // Grabamos lo que teníamos bindeado al form
                $this->directorrelacionTable->saveDirectorRelacion($directorRelacion);

                // Grabamos aparte el email
                $this->directorrelacionTable->updateEmail($id, $directorRelacion->email, $encryptKey); //saveEmail($id, $encryptKey, $directorRelacion->email);

                // Si los datos antiguos de oficina y los actuales son distintos, borramos la entrada en ultimos_datos_oficina
                $directorRelacionPost = $this->directorrelacionTable->getDirectorRelacion($id, $encryptKey);
                if ($idOficinaOld != $directorRelacionPost->idoficina)
                {
                    $this->ultimosdatosTable->deletePorDR($directorRelacion->id);
                }


                // Vamos a grabar el login del usuario en la table
                $gestorusuarios = $this->gestorusuariosTable->getEntityModel();
                $gestorusuarios->login = $post['login'];
                // Ahora hacemos un update del usuario en gestorusuarios
                $this->gestorusuariosTable->updateLogin($directorRelacion->idusuario, $post['login']);

            }

            return $this->goToSection('director_relacion',array(
                'action' => 'edit',
                'id' => $id
            ));
        }


        return new ViewModel(array(
            'id'          => $id,
            'form'        => $form,
            'directorRelacion'      => $directorRelacion,
            'cuantosEquipos'    => $cuantosEquipos,
            'addOK' => $addOK,
        ));

    }

    public function deleteAction()
    {

    }
}