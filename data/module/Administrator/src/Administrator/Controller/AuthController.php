<?php

namespace Administrator\Controller;

use Administrator\Service\AuthService;
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

    /**
     * @var AuthService
     */
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
        $profilePermissionService,
        $datatableService,
        $viewRenderer
    ) {
        $this->config = $config;
        $this->sessionService = $sessionService;
        $this->authService = $authService;
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

    /**
     * @param $module
     * @param array $params
     * @return string
     */
    public function getUrlSection($module, $params = [])
    {
        $defaultParams = [
            'module' => $module
        ];

        if (!array_key_exists('action', $params)) {
            $params['action'] = 'index';
        }

        $defaultParams = array_merge($defaultParams, $params);

        return $this->url()->fromRoute('administrator', $defaultParams);
    }

    /**
     * @param $module
     * Sección del gestor al que redirigimos
     *
     * @param array $params
     * Parámetros opcionales
     * @param array $options
     * @return \Laminas\Http\Response
     */
    public function goToSection($module, $params = [], $options = [])
    {
        $defaultParams = [
            'module' => $module
        ];

        if (!array_key_exists('action', $params)) {
            $params['action'] = 'index';
        }

        $defaultParams = array_merge($defaultParams, $params);

        return $this->redirect()->toRoute('administrator', $defaultParams, $options);
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

    private function accessErrorHandler($errorKey)
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
        if (!$this->authService->hasIdentity()) {
            $this->sessionService->section_referer = $this->event->getRouteMatch()->getParams();
            $this->sessionService->query_params = $this->getRequest()->getQuery()->toArray();

            return 'ACCESS_SESSION_EXPIRED';
        }

        $userData = $this->authService->getUserData();

        $this->layout()->userData = $userData;

        $isActiveUser = (bool) $userData->active;

        if (!$isActiveUser) {
            $this->authService->clearIdentity();
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

    protected function getView($viewName, $params = [])
    {
        return (new ViewModel($params))->setTemplate('administrator/' . $viewName);
    }

    protected function parseTriggers()
    {
        $html = '';
        foreach ($this->triggerResults as $result) {
            $html .= $this->viewRenderer->render($result);
        }

        return $html;
    }
}
