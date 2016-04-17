<?php
namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

use Gestor\Form\CoordinadorForm;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;

class CoordinadorController extends AuthController implements ControllerInterface
{
    protected $datatable;
    protected $coordinadorTable;
    protected $gestorusuariosTable;

    public function setControllerVars()
    {
        $this->datatable = $this->sm->get('Gestor\Service\DatatableService');
        $this->coordinadorTable = $this->sm->get('Gestor\Model\CoordinadorTable');
        $this->gestorusuariosTable = $this->sm->get('Gestor\Model\GestorUsuariosTable');
    }

    public function indexAction()
    {
        // Primero sacamos un array de los DRs
        $arrayCoordinadores = $this->coordinadorTable->fetchAllData($this->config['encrypt_key']);


        return new ViewModel(array(
            'arrayCoordinadores' => $arrayCoordinadores
        ));

    }

    public function addAction()
    {

        // Grabado de los datos
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();

            // Creamos la nueva entrada en gestorusuarios
            $unUsuario['login'] = $_REQUEST['login'];
            $unUsuario['idPerfil'] = 2; // Perfil de coordinador
            $unUsuario['activo'] = 1;
            $unUsuario['validado'] = 0;
            $clave =  $this->config['clavePorDefecto'];
            $encryptKey = $this->config['encrypt_key'];
            $idUsuarioResultado = $this->gestorusuariosTable->GrabarExcel($unUsuario, $encryptKey, $clave);

            // Creamos la nueva entrada en coordinador
            $idUsuarioCoordinador = $this->coordinadorTable->GrabarCoordinador($idUsuarioResultado, $encryptKey, $_REQUEST['email']);

            // Redirigimos al edit con el nuevo id de coordinador
            return $this->goToSection('coordinador',array(
                'action' => 'edit',
                'id' => $idUsuarioCoordinador
            ));
        }

        return new ViewModel();
    }




    public function editAction()
    {

        // Vamos a sacar/grabar los datos
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('coordinador');
        }

        // Sacamos los datos de una entrada en concreto
        try {
            $encryptKey = $this->config['encrypt_key'];
            $coordinador = $this->coordinadorTable->getElementoIdTable($id, $encryptKey);
        } catch (\Exception $ex) {
            return $this->goToSection('coordinador');
        }

        $arrayFinal['email'] = $coordinador->email;
        $arrayFinal['idUsuario'] = $coordinador->idUsuario;
        $arrayFinal['id'] = $coordinador->id;

        $usuarioGestorData = $this->gestorusuariosTable->getGestorUsuarios($coordinador->idUsuario);
        $arrayFinal['fechaAlta'] = $usuarioGestorData->fechaAlta;
        $arrayFinal['ultimoLogin'] = $usuarioGestorData->ultimoLogin;
        $arrayFinal['login'] = $usuarioGestorData->login;


        // Grabado de los datos
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            // Grabamos el nuevo correo electrónico
            $this->coordinadorTable->updateEmail($coordinador->idUsuario, $_REQUEST['email'], $encryptKey);
            // Grabamos el nuevo login
            $this->gestorusuariosTable->updateLogin($coordinador->idUsuario,$_REQUEST['login']);

            return $this->goToSection('coordinador',array(
                'action' => 'edit',
                'id' => $id
            ));
        }


        return new ViewModel(array(
            'arrayCoordinador' => $arrayFinal
        ));

    }

    public function deleteAction()
    {

    }




    public function desactivarAction()
    {
        // Afectamos a lo que recibimos como $id
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('coordinador');
        }

        // Obtenemos el id en la tabla de gestorusuarios
        try {
            $coordinador = $this->coordinadorTable->getElementoIdTable($id, $this->config['encrypt_key']);
        } catch (\Exception $ex) {
            return $this->goToSection('coordinador');
        }
        $idUsuario = $coordinador->idUsuario;

        $this->gestorusuariosTable->updateActivo($idUsuario, 0);

        return $this->goToSection('coordinador',array(
            'action' => 'index'
        ));

    }

    public function activarAction()
    {
        // Afectamos a lo que recibimos como $id
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('coordinador');
        }

        // Obtenemos el id en la tabla de gestorusuarios
        try {
            $directorRelacion = $this->coordinadorTable->getElementoIdTable($id, $this->config['encrypt_key']);
        } catch (\Exception $ex) {
            return $this->goToSection('coordinador');
        }
        $idUsuario = $directorRelacion->idUsuario;

        $this->gestorusuariosTable->updateActivo($idUsuario, 1);

        return $this->goToSection('coordinador',array(
            'action' => 'index'
        ));

    }







}