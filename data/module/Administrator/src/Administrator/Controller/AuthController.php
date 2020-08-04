<?php

namespace Administrator\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

abstract class AuthController extends AbstractActionController
{
    protected $errorMessages = [
        'ACCESS_SESSION_EXPIRED' => 'La sesión ha caducado',
        'ACCESS_USER_DEACTIVATE' => 'Tu usuario ha sido desactivado',
        'ACCESS_PERMISSION_DENIED' => 'Acceso denegado'
    ];

    // Whitelist de rutas con las que no se muestra login
    protected $whitelist = ['login'];

    protected $storage;
    protected $authService;
    protected $triggerResults;

    protected $sessionService;
    protected $profilePermissionService;
    protected $tableGateway;
    protected $formService;
    protected $datatableService;
    protected $datatableConfigService;
    protected $viewRenderer;
    protected $model;

    protected $config;

    public function __construct(
        $config,
        $sessionService,
        $authService,
        $storage,
        $profilePermissionService,
        $datatableService,
        $viewRenderer
    ) {
        $this->config = $config;
        $this->sessionService = $sessionService;
        $this->authService = $authService;
        $this->storage = $storage;
        $this->profilePermissionService = $profilePermissionService;
        $this->datatableService = $datatableService;
        $this->viewRenderer = $viewRenderer;
    }

    public function setDatatableConfigService($datatableConfigService)
    {
        $this->datatableConfigService = $datatableConfigService;
        return $this;
    }

    public function setFormService($formService)
    {
        $this->formService = $formService;
        return $this;
    }

    public function setTableGateway($tableGateway)
    {
        $this->tableGateway = $tableGateway;
        return $this;
    }

    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getAuthService($returnAuthInstance = true)
    {
        return $returnAuthInstance ? $this->authService->getAuthInstance() : $this->authService;
    }

    public function getSessionStorage()
    {
        return $this->storage;
    }

    /**
     * @param $module
     * Sección del gestor al que redirigimos
     *
     * @param array $params
     * Parámetros opcionales
     *
     * @return \Laminas\Http\Response
     */
    public function goToSection($module, $params = [], $returnLink = false, $options = [])
    {
        $defaultParams = [
            'module' => $module
        ];

        if (!array_key_exists('action', $params)) {
            $params['action'] = 'index';
        }

        $defaultParams = array_merge($defaultParams, $params);

        return $returnLink
            ? $this->url()->fromRoute('administrator', $defaultParams, $options)
            : $this->redirect()->toRoute('administrator', $defaultParams, $options);
    }

    public function gotoAddSection($module, $returnLink = false, $options = [])
    {
        return $this->goToSection($module, ['action' => 'add'], $returnLink, $options);
    }

    public function goToEditSection($module, $id, $returnLink = false, $options = [])
    {
        return $this->goToSection($module, ['action' => 'edit', 'id' => $id], $returnLink, $options);
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

        $this->getAuthService()->getStorage()->write([
            'user' => $username,
            'password' => $password
        ]);

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
        $routeMatch = $e->getRouteMatch();

        $module = $routeMatch->getParam('module');
        $action = $routeMatch->getParam('action');

        if ($module == '') {
            return $this->goToSection('login');
        }

        $this->layout('layout/admin-login-layout');

        $this->layout()->adminCssVersion = $this->config['admin_css_assets_version'];
        $this->layout()->adminJsVersion = $this->config['admin_js_assets_version'];

        if (!in_array($module, $this->whitelist)) {
            $this->layout('layout/admin-layout');

            $canAccess = $this->canAccess($module, $action);

            if ($canAccess !== true) {
                return $this->accessErrorHandler($canAccess);
            }
        }

        $this->triggerResults = $this->runAdministratorTrigger(
            $routeMatch->getParam('module'),
            $routeMatch->getParam('action')
        );

        return parent::onDispatch($e);
    }

    protected function accessErrorHandler($errorKey)
    {
        $jsonModel = new JsonModel([
            'status' => 'ok',
            'response' => 'false',
            'error' => true,
            'message' => $this->errorMessages[$errorKey]
        ]);

        if ($this->getRequest()->isXmlHttpRequest()) {
            $response = $this->getResponse();
            $response->setContent($jsonModel->serialize());
            return $response;
        }

        $this->flashMessenger()->addMessage($this->errorMessages[$errorKey]);
        return $this->goToSection('login');
    }

    private function canAccess($module, $action)
    {
        // Comprobamos si esta autenticado
        $authService = $this->getAuthService();

        if (!$authService->hasIdentity()) {
            $this->sessionService->section_referer = $this->event->getRouteMatch()->getParams();
            $this->sessionService->query_params = $this->getRequest()->getQuery()->toArray();

            return 'ACCESS_SESSION_EXPIRED';
        }

        $userData = $this->getUserData();

        $this->layout()->userData = $userData;

        if (!$this->isActiveUser($userData)) {
            return 'ACCESS_USER_DEACTIVATE';
        }

        if (!$this->profilePermissionService->hasModuleAccess($module, $action)) {
            return 'ACCESS_PERMISSION_DENIED';
        }

        return true;
    }

    private function runAdministratorTrigger($module, $action)
    {
        $eventManager = $this->getEventManager();

        $requestMethod = strtolower($this->getRequest()->getMethod());

        $triggerName = $requestMethod . '.' . $module . '.' . $action;

        return $eventManager->trigger($triggerName, null);
    }

    protected function getView($params = [], $viewName)
    {
        $viewModel = new ViewModel($params);
        $viewModel->setTemplate('administrator/' . $viewName);

        return $viewModel;
    }

    public function getAddView($params = [])
    {
        return $this->getView($params, 'add');
    }

    public function getEditView($params = [])
    {
        return $this->getView($params, 'edit');
    }

    public function parseTriggers()
    {
        $html = '';
        foreach ($this->triggerResults as $result) {
            $html .= $this->viewRenderer->render($result);
        }

        return $html;
    }
}
