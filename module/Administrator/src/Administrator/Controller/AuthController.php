<?php

namespace Administrator\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthController extends AbstractActionController
{
    // Whitelist de rutas con las que no se muestra login
    protected $whitelist = array('login');
    protected $sm;
    protected $storage;
    protected $authservice;
    protected $formService;
    protected $sessionService;
    protected $config;

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->sm->get('AuthService');
        }

        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->sm->get('Administrator\Model\AuthStorage');
        }

        return $this->storage;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->sm = $serviceLocator;
        parent::setServiceLocator($serviceLocator);
    }

    /**
     * @param $module
     * Sección del gestor al que redirigimos
     *
     * @param array $params
     * Parámetros opcionales
     *
     * @return \Zend\Http\Response
     */
    public function goToSection($module, $params = array(), $returnLink = false)
    {
        $defaultParams = array(
            'module' => $module
        );

        if (!array_key_exists('action', $params)) {
            $params['action'] = 'index';
        }

        $defaultParams = array_merge($defaultParams, $params);

        return $returnLink
            ? $this->url()->fromRoute('administrator', $defaultParams)
            : $this->redirect()->toRoute('administrator', $defaultParams);
    }

    protected function getUserData()
    {
        $identity = $this->getAuthService()->getIdentity();
        return $this->sessionService->getUserData($identity['user']);
    }

    protected function forbidUser()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
    }

    protected function checkUser($username, $password)
    {
        $authService = $this->getAuthService();

        $authService
            ->getAdapter()
            ->setIdentity($username)
            ->setCredential($password);

        $result = $authService->authenticate();

        $this->getAuthService()->getStorage()->write(array(
            'user' => $username,
            'password' => $password
        ));

        if ($result->getCode() != 1) {
            $this->forbidUser();
        }

        return $result;
    }

    public function isActiveUser($userData)
    {
        $activo = (bool) $userData->active;

        if (!$activo) {
            $this->forbidUser();
        }

        return $activo;
    }

    public function onDispatch(MvcEvent $e)
    {
        // Sacamos la ruta para matchearla
        $match = $e->getRouteMatch();
        $this->sessionService = $this->sm->get('Administrator\Service\SessionServiceInterface');
        $this->config = $this->sm->get('Config');


        $module = $match->getParam('module');
        $action = $match->getParam('action');

        if ($module == "") {
            return $this->goToSection('login');
        }

        $this->layout('layout/admin-login-layout');

        if (!in_array($module, $this->whitelist)) {

            $this->layout('layout/admin-layout');

            // Comprobamos si esta autenticado
            $authService = $this->getAuthService();
            if (!$authService->hasIdentity()){
                $_SESSION['section_referer'] = $match->getParams();
                return $this->goToSection('login');
            }

            $userData = $this->getUserData();

            $this->layout()->userData = $userData;

            if (!$this->isActiveUser($userData)) {
                $this->flashMessenger()->addMessage("Tu usuario ha sido desactivado");
                return $this->goToSection('login');
            }

            $misPermisos = $this->sm->get('Administrator\Factory\PermisosCheckerFactory');

            $hasAccess = $misPermisos->hasModuleAccess($module,$action);

            if (!$hasAccess)
            {
                return $this->redirect()->toRoute('administrator',array(
                    'module' => 'home',
                ));
            }
        }

        return parent::onDispatch($e);
    }
}