<?php
namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;

use Gestor\Form\CoordinadorForm;



class MisDatosController extends AuthController implements ControllerInterface
{

    protected $perfilTable;
    protected $coordinadorTable;
    protected $gestorUsuariosTable;

    public function setControllerVars()
    {

        $this->perfilTable = $this->sm->get('Gestor\Model\PerfilTable');
        $this->coordinadorTable = $this->sm->get('Gestor\Model\CoordinadorTable');
        $this->gestorUsuariosTable = $this->sm->get('Gestor\Model\GestorUsuariosTable');
        $this->directorRelacionTable = $this->sm->get('Gestor\Model\DirectorRelacionTable');
        //$this->formService      = $this->sm->get('Gestor\Service\GestorFormService')->setTable($this->coordinadorTable);
    }



    public function coordinadorAction()
    {

        $userData = $this->getUserData();
        $idUser = $userData->id;

        $datosCoordinador = $this->coordinadorTable->getElemento($userData->id, $this->config['encrypt_key']);

        $datosGestorUsuarios = $this->gestorUsuariosTable->getGestorUsuarios($userData->id);

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();

        if ($request->isPost()) {

            // Si hay password lo cambiamos
            if ($_REQUEST['checkPassword'] == 1)
            {
                $data['password'] = $_REQUEST['password'];
                $data['password2'] = $_REQUEST['password2'];
                if ($data['password'] != $data['password2'])
                {
                    return new ViewModel(array(
                        'datosCoordinador'          => $datosCoordinador,
                        'datosGestorUsuarios'       => $datosGestorUsuarios,
                        'error'                     => 'Las claves no coinciden',
                    ));
                }
                // Grabamos la pass
                $this->gestorUsuariosTable->updatePassword($idUser,$data['password'],$this->config['encrypt_key']);

                $this->checkUser($userData->login, $data['password']);
            }

            $data['email'] = $_REQUEST['email'];
            $this->coordinadorTable->updateEmail($idUser, $data['email'], $this->config['encrypt_key']);

            return $this->goToSection('misdatos', array(
                'action' => 'coordinador',
            ));
        }

        return new ViewModel(array(
            'datosCoordinador'          => $datosCoordinador,
            'datosGestorUsuarios'       => $datosGestorUsuarios,
        ));
    }

    public function directorAction()
    {

        $userData = $this->getUserData();
        $idUser = $userData->id;

        $datosDirectorRelacion = $this->directorRelacionTable->getDirectorRelacionPorUsuario($userData->id, $this->config['encrypt_key']);

        $datosGestorUsuarios = $this->gestorUsuariosTable->getGestorUsuarios($userData->id);

        // Si es un request tipo post grabamos los datos y redirigimos
        $request = $this->getRequest();

        if ($request->isPost()) {

            // Si hay password lo cambiamos
            if ($_REQUEST['checkPassword'] == 1)
            {
                $data['password'] = $_REQUEST['password'];
                $data['password2'] = $_REQUEST['password2'];
                if ($data['password'] != $data['password2'])
                {
                    return new ViewModel(array(
                        'datosDirectorRelacion'          => $datosDirectorRelacion,
                        'datosGestorUsuarios'       => $datosGestorUsuarios,
                        'error'                     => 'Las claves no coinciden',
                    ));
                }
                // Grabamos la pass
                $this->gestorUsuariosTable->updatePassword($idUser,$data['password'],$this->config['encrypt_key']);

                $this->checkUser($userData->login, $data['password']);

            }

            $data['email'] = $_REQUEST['email'];
            $data['nombre'] = $_REQUEST['nombre'];
            $data['apellido1'] = $_REQUEST['apellido1'];
            $data['apellido2'] = $_REQUEST['apellido2'];
            $data['movil'] = $_REQUEST['movil'];
            $data['trato'] = $_REQUEST['trato'];
            // TODO Ahora actualizamos los datos del director
            $this->directorRelacionTable->updateMyData($idUser, $data, $this->config['encrypt_key']);

            //$this->coordinadorTable->updateEmail($idUser, $data['email'], $this->config['encrypt_key']);

            return $this->goToSection('misdatos', array(
                'action' => 'director',
            ));
        }

        return new ViewModel(array(
            'datosDirectorRelacion'          => $datosDirectorRelacion,
            'datosGestorUsuarios'       => $datosGestorUsuarios,
        ));
    }



    public function indexAction()
    {

        // 1. Sacamos el id de perfil del usuario
        $userData = $this->getUserData();
        $idPerfil = $userData->idPerfil;

        switch ($idPerfil)
        {
            case 1: // Superadministrador
            case 4: // Administrador
                break;


            case 2: // Coordinador
                return $this->goToSection('misdatos', array(
                    'action'  => 'coordinador'
                ));

            case 3: // Director de relación
                return $this->goToSection('misdatos', array(
                    'action'  => 'director'
                ));
        }

        return new ViewModel(); // Vamos a pintar que esta sección no tiene mucho sentido


    }
}