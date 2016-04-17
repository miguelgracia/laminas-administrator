<?php
namespace Gestor\Controller;

use Gestor\Form\DatosAccesoForm;
use Gestor\Form\RecordarPasswordForm;
use Zend\Db\Sql\Expression;
use Zend\View\Model\ViewModel;


class LoginController extends AuthController implements ControllerInterface
{
    public function setControllerVars(){}

    protected $data = array();

    public function indexAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->logoutAction();
        }
        $this->layout('layout/login');

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

    public function editarDatosUsuarioAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            $request = $this->getRequest();

            $userData = $this->getUserData();
            $form = new DatosAccesoForm('datos-acceso');
            $form->addFields($userData);

            $message = '';

            if ($userData->idPerfil == 3) {
                $message = "Más adelante, podrás cambiar el resto de datos en tu página de usuario";
            }

            if ($request->isPost()) {

                $encryptKey = $this->config['encrypt_key'];

                $usuarioTable = $this->sm->get('Gestor\Model\GestorUsuariosTable');
                $usuarioModel = $usuarioTable->getEntityModel();

                if ($userData->idPerfil == 2) {
                    $coordinadorTable = $this->sm->get('Gestor\Model\CoordinadorTable');
                    $coordinadorModel = $coordinadorTable->getEntityModel();
                    $form->setInputFilter($coordinadorModel->getInputFilterPreRegistro())->setData($request->getPost());
                } elseif ($userData->idPerfil == 3) {
                    $directorRelacionTable = $this->sm->get('Gestor\Model\DirectorRelacionTable');
                    $directorRelacionModel = $directorRelacionTable->getEntityModel();
                    $form->setInputFilter($directorRelacionModel->getInputFilterPreRegistro())->setData($request->getPost());
                }

                if ($form->isValid()) {
                    if ($userData->idPerfil == 2) {
                        //Perfil coordinador
                        $coordinadorModel->exchangeArray($form->getData());
                        $coordinadorModel->id_usuario = $userData->id;
                        $email = $coordinadorModel->email;
                        $coordinadorModel->email = new Expression("AES_ENCRYPT('$email','$encryptKey')");
                        $coordinadorTable->saveCoordinador($coordinadorModel);

                    } elseif ($userData->idPerfil == 3) {
                        //Perfil director relacion
                        $directorRelacionModel->exchangeArray($form->getData());
                        $directorRelacionModel->idusuario = $userData->id;
                        $email = $directorRelacionModel->email;
                        $directorRelacionModel->email = new Expression("AES_ENCRYPT('$email','$encryptKey')");
                        $directorRelacionTable->saveDirector($directorRelacionModel);
                    }

                    $usuarioModel->exchangeArray($form->getData());

                    $password = $usuarioModel->password;

                    $usuarioModel->password = new Expression("AES_ENCRYPT('$password','$encryptKey')");
                    $usuarioModel->validado = 1;
                    $usuarioModel->login = $userData->login;
                    $usuarioModel->idPerfil = $userData->idPerfil;
                    $usuarioModel->id = $userData->id;

                    $usuarioTable->saveGestorUsuarios($usuarioModel);

                    $this->checkUser($userData->login, $password);

                    return $this->goToSection('home');

                }
            }

            return new ViewModel(array(
                'form' => $form,
                'userData' => $userData,
                'message' => $message
            ));
        }

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
                            if ($userData->idPerfil == 2) {
                                $coordinadorTable = $this->sm->get('Gestor\Model\CoordinadorTable');
                                $coordinador = $coordinadorTable->getCoordinador(array(
                                    'id_usuario' => $userData->id,
                                    'email' => new Expression("AES_ENCRYPT('$email','$encryptKey')")
                                ));

                                $userFound = false;
                                if ($coordinador) {

                                    $userFound = true;
                                    $viewData['password'] = $userData->password;
                                }
                                $viewData['userFound'] = $userFound;

                                //Comprobamos el email en la tabla de coordinadores
                            } elseif ($userData->idPerfil == 3) {
                                $directorRelacionTable = $this->sm->get('Gestor\Model\DirectorRelacionTable');
                                //Comprobamos el email en la tabla de directores de relacion
                                $director = $directorRelacionTable->getDirector(array(
                                    'idusuario' => $userData->id,
                                    'email' => new Expression("AES_ENCRYPT('$email','$encryptKey')")
                                ));

                                $userFound = false;
                                if ($director) {

                                    $userFound = true;
                                    $viewData['password'] = $userData->password;
                                    $_SESSION['foundPassword'] = $userData->password;
                                }
                                $viewData['userFound'] = $userFound;

                            }
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
                    if (isset($_SESSION['section_referer'])) {
                        $params = $_SESSION['section_referer'];
                        $redirectTo = $params['section'];
                        unset($params['section']);
                        $redirectParams = $params;
                        unset($_SESSION['section_referer']);
                    } else {
                        $redirectTo = 'home';
                    }


                    $this->getSessionStorage()->setRememberMe(1);
                    $this->saveLastLogin($userCheck,$passwordCheck);
                }

            } catch (\Exception $ex) {
                $this->flashMessenger()->addMessage("Usuario o password incorrectos.");
                $redirectTo = 'login';
            }
        }

        return $this->goToSection($redirectTo, $redirectParams);
    }

    private function saveLastLogin($userCheck, $passwordCheck)
    {
        $usuariosTable       = $this->sm->get('Gestor\Model\GestorUsuariosTable');
        $historicoLoginTable = $this->sm->get('Gestor\Model\HistoricoLoginTable');

        $encryptKey = $this->config['encrypt_key'];

        $usuariosTable->updateLastLogin($userCheck,new Expression("AES_ENCRYPT('$passwordCheck','$encryptKey')"));

        $userData = $this->getUserData();

        $historicoLoginTable->saveLogin(array(
            'id_usuario' => $userData->id,
            'ip'         => $this->getRequest()->getServer('REMOTE_ADDR'),
            'id_perfil'  => $userData->idPerfil,
            'fecha'      => date('Y-m-d H:i:s')
        ));
    }
}