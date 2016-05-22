<?php
namespace AmLogin\Controller;

use Administrator\Controller\AuthController;
use Administrator\Form\DatosAccesoForm;
use Administrator\Form\RecordarPasswordForm;

use Zend\View\Model\ViewModel;


class AmLoginModuleController extends AuthController
{
    public function setControllerVars() {

    }

    protected $data = array();

    public function indexAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->logoutAction();
        }

        if (isset($_SESSION['userFound']) and $_SESSION['userFound']) {
            $this->data['userFound'] = true;
            $this->data['foundPassword'] = $_SESSION['foundPassword'];
        }

        unset($_SESSION['userFound']);
        unset($_SESSION['foundPassword']);

        return new ViewModel($this->data);
    }

    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashmessenger()->addMessage("Ha salido de la plataforma.");
        return $this->goToSection('login');
    }

    public function rememberPasswordAction()
    {
        if (!$this->getAuthService()->hasIdentity()) {
            $this->layout('layout/login');
            $form = new RecordarPasswordForm('recordar-password');

            $request = $this->getRequest();

            $viewData = array();

            if ($request->isPost()) {

                $form->bind($request->getPost());

                if ($form->isValid()) {
                    $userTable = $this->sm->get('Gestor\Model\GestorUsuariosTable');

                    $userData = $userTable->getUserdata($request->getPost('login'),$this->config['encrypt_key']);
                    $email = $request->getPost('email');

                    if ($userData) {
                        //Comprobamos que el usuario no sea superadmin. Dichos usuarios, por seguridad,
                        //no podrán ver su password mediante este método.
                        $encryptKey = $this->config['encrypt_key'];
                        if ($userData->idPerfil != 1) {

                        } else {
                            return $this->goToSection('login');
                        }
                    } else {
                        $viewData['userFound'] = false;
                    }

                    if ($viewData['userFound'] == true) {
                        $_SESSION['userFound'] = true;
                        return $this->goToSection('login');
                    }
                }
            }

            $viewData['form'] = $form;

            return new ViewModel($viewData);
        }

        return $this->goToSection('home');

    }

    public function checkLoginAction()
    {
        $redirectTo = 'login';
        $redirectParams = array();
        if ($this->getRequest()->isPost()) {
            // sacamos los datos del login
            $postFields = $this->params()->fromPost();

            $userCheck = $postFields['user'];
            $passwordCheck = $postFields['password'];

            try {
                $result = $this->checkUser($userCheck,$passwordCheck);

                if ($result->getCode() != $result::SUCCESS) {
                    $this->flashMessenger()->addMessage("Usuario o password incorrectos");
                }

                if ($result->isValid()) {
                    if ($this->sessionService->section_referer) {
                        $params = $this->sessionService->section_referer;
                        $redirectTo = $params['module'];
                        unset($params['module']);
                        $redirectParams = $params;

                        $this->sessionService->section_referer = null;
                    } else {
                        $redirectTo = 'home';
                    }


                    //$this->getSessionStorage()->setRememberMe(0);
                    $this->saveLastLogin($userCheck,$passwordCheck);
                }

            } catch (\Exception $ex) {
                $this->flashMessenger()->addMessage($ex->getMessage() . "Usuario o password incorrectos.");
                $redirectTo = 'login';
            }
        }

        return $this->goToSection($redirectTo, $redirectParams);
    }

    private function saveLastLogin($userCheck, $passwordCheck)
    {
        /*$usuariosTable       = $this->sm->get('Gestor\Model\GestorUsuariosTable');
        $historicoLoginTable = $this->sm->get('Gestor\Model\HistoricoLoginTable');

        $encryptKey = $this->config['encrypt_key'];

        $usuariosTable->updateLastLogin($userCheck,new Expression("AES_ENCRYPT('$passwordCheck','$encryptKey')"));

        $userData = $this->getUserData();

        $historicoLoginTable->saveLogin(array(
            'id_usuario' => $userData->id,
            'ip'         => $this->getRequest()->getServer('REMOTE_ADDR'),
            'id_perfil'  => $userData->idPerfil,
            'fecha'      => date('Y-m-d H:i:s')
        ));*/
    }
}