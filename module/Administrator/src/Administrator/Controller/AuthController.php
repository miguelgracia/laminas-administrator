<?php

namespace Administrator\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

abstract class AuthController extends AbstractActionController
{
    // Whitelist de rutas con las que no se muestra login
    protected $whitelist = array('login');

    protected $storage;
    protected $authService;

    protected $tableGateway;

    protected $triggerResults;

    protected $sessionService;
    protected $config;

    public function getAuthService($returnAuthInstance = true)
    {
        if (! $this->authService) {
            $this->authService = $this->serviceLocator->get('AuthService');
        }

        return $returnAuthInstance ? $this->authService->getAuthInstance() : $this->authService;
    }

    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->serviceLocator->get('Administrator\Model\AuthStorage');
        }

        return $this->storage;
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

    public function gotoAddSection($module, $returnLink = false)
    {
        return $this->goToSection($module,array('action' => 'add'),$returnLink);
    }

    public function goToEditSection($module, $id, $returnLink = false)
    {
        return $this->goToSection($module,array('action' => 'edit', 'id' => $id), $returnLink);
    }

    protected function getUserData()
    {
        return $this->getAuthService(false)->getUserData();
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
        $this->setControllerVars();

        $this->sessionService   = $this->serviceLocator->get('Administrator\Service\SessionService');

        // Sacamos la ruta para matchearla
        $match = $e->getRouteMatch();

        $this->config = $this->serviceLocator->get('Config');


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
                $this->sessionService->section_referer = $match->getParams();
                return $this->goToSection('login');
            }

            $userData = $this->getUserData();

            $this->layout()->userData = $userData;

            if (!$this->isActiveUser($userData)) {
                $this->flashMessenger()->addMessage("Tu usuario ha sido desactivado");
                return $this->goToSection('login');
            }

            $permissionsService = $this->serviceLocator->get('AmProfile\Service\ProfilePermissionService');

            if (!$permissionsService->hasModuleAccess($module,$action))
            {
                return $this->redirect()->toRoute('administrator',array(
                    'module' => 'login',
                ));
            }
        }

        $this->triggerResults = $this->runAdministratorTrigger($match);

        return parent::onDispatch($e);
    }

    private function runAdministratorTrigger(RouteMatch $match)
    {
        $eventManager = $this->getEventManager();

        $requestMethod = strtolower($this->getRequest()->getMethod());

        $module = $match->getParam('module');
        $action = $match->getParam('action');

        $triggerName = $requestMethod . '.' . $module . '.' . $action;

        return $eventManager->trigger($triggerName, null, array('serviceLocator' => $this->serviceLocator));
    }

    public function setControllerVars()
    {
        $className = get_class($this);

        $tableGateway = preg_replace('/^(Am)(\w+)\\\(\w+)\\\(\w+)(ModuleController)$/', "$1$2\\Model\\\\$2Table", $className);

        if (class_exists($tableGateway)) {
            $this->tableGateway = $this->serviceLocator->get($tableGateway);
        }
    }

    protected function getView($params = array(), $viewName)
    {
        $viewModel = new ViewModel($params);
        $viewModel->setTemplate('administrator/'.$viewName);

        return $viewModel;
    }


    public function getAddView($params = array())
    {
        return $this->getView($params,'add');
    }

    public function getEditView($params = array())
    {
        return $this->getView($params,'edit');
    }

    public function parseTriggers()
    {
        $html = "";
        $viewRenderer = $this->serviceLocator->get('ViewRenderer');
        foreach ($this->triggerResults as $result) {
            $html .= $viewRenderer->render($result);
        }

        return $html;
    }
}