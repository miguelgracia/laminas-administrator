<?php
namespace Gestor\Controller;

use Zend\View\Model\ViewModel;

// Para formularios
use Zend\Captcha;
use Zend\Form\Element;


class PermisosController extends AuthController implements ControllerInterface
{
    protected $permisosTable;
    protected $perfilTable;
    protected $entradaMenuTable;
    protected $gestorControladorTable;

    public function setControllerVars()
    {
        $this->permisosTable          = $this->sm->get('Gestor\Model\PermisosTable');
        $this->perfilTable            = $this->sm->get('Gestor\Model\PerfilTable');
        $this->entradaMenuTable       = $this->sm->get('Gestor\Model\EntradaMenuTable');
        $this->gestorControladorTable = $this->sm->get('Gestor\Model\GestorControladorTable');
    }

    // **************** Acciones del controlador ****************
    public function indexAction()
    {
        // Listado de perfiles para elegir cuál tocar.
        $arrayPerfiles = $this->perfilTable->fetchAll();

//$listaControladoresTEMP = $this->gestorControladorTable->fetchWithParent(2);

        return new ViewModel(array(
            'perfiles' => $arrayPerfiles,
        ));
    }



    // *************** AJAX QUE LISTA ********************
    public function listAction()
    {
        $esAdmin = 0;

        $listaPermisos = array();
        $listaControladores = array();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $reqData = $request->getPost();
            $idPerfil = $reqData->idPerfil;

            // Vamos a ver si el idPerfil corresponde a un admin, en cuyo caso esto no tiene sentido y lo indicamos
            $data = $this->perfilTable->getPerfil($idPerfil);
            $esAdmin = $data->esAdmin;

            if ($esAdmin != 1)
            {
                // Primero sacamos una lista de todos los controladores sobre los que queremos determinar el acceso
                // Más bien una lista de todas las rutas que tengan el tieneEnlace a 1

                // Vamos a ver si el perfil que estamos editando tiene o no padres.
                if ($data->idPerfilPadre == '')
                {
                    $listaControladores = $this->gestorControladorTable->fetchAll();
                } else {
                    $listaControladores = $this->gestorControladorTable->fetchWithParent($data->idPerfilPadre);
                }



                // Luego vemos en la lista de permisos cuáles de estos tiene como SI.
                $listaPermisos = $this->permisosTable->fetchAllPerfil($idPerfil);
            }
        }

        $this->layout('layout/blank');

        $viewModel = new ViewModel(array(
            'esAdmin'            => $esAdmin,
            'listaControladores' => $listaControladores,
            'listaPermisos'      => $listaPermisos,
            'idPerfil'           => $idPerfil,
        ));

        return $viewModel;
    }

    // Vamos a grabar los permisos de un perfil
    public function writeAction()
    {
        $idPerfil = $this->params()->fromPost('idPerfil');

        // Sacamos el id de perfil, devolvemos al principal si no existe
        if (!$idPerfil)
        {
            $this->flashMessenger()->addMessage('Error cambiando los permisos');
            return $this->goToSection('permisos');
        }

        // Borramos lo que hay en permisos para ese ID de perfil
        $this->permisosTable->deleteAllPerfil($idPerfil);

        // Sacamos lo que hemos recibido activado en el form
        $arrayPermisos = $this->params()->fromPost('permisos');

        // Escribimos cada permiso en la tabla
        if (is_array($arrayPermisos)) {
            foreach ($arrayPermisos as $permiso)
            {
                $this->permisosTable->insertPermiso($idPerfil, $permiso);
            }

            // Listado de perfiles para elegir cuál tocar.
            $perfilData = $this->perfilTable->getPerfil($idPerfil);

            // Aqui el flash messenger para que te diga que mu bien
            $this->flashMessenger()->addMessage('Permisos cambiados en el perfil '.$perfilData->nombre);
        }

        return $this->goToSection('permisos');
    }
}