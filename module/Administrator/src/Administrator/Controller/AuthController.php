<?php

namespace Administrator\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    // Whitelist de rutas con las que no se muestra login
    protected $whitelist = array('login');
    protected $sm;
    protected $storage;
    protected $authService;

    protected $tableGateway;
    protected $formService;

    protected $sessionService;
    protected $config;

    public function getAuthService($returnAuthInstance = true)
    {
        if (! $this->authService) {
            $this->authService = $this->sm->get('AuthService');
        }

        return $returnAuthInstance ? $this->authService->getAuthInstance() : $this->authService;
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

        $this->sessionService   = $this->sm->get('Administrator\Service\SessionService');
        $this->formService      = $this->sm->get('Administrator\Service\AdministratorFormService');


        // Sacamos la ruta para matchearla
        $match = $e->getRouteMatch();

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
                $this->sessionService->section_referer = $match->getParams();
                return $this->goToSection('login');
            }

            $userData = $this->getUserData();

            $this->layout()->userData = $userData;

            if (!$this->isActiveUser($userData)) {
                $this->flashMessenger()->addMessage("Tu usuario ha sido desactivado");
                return $this->goToSection('login');
            }

            $permissionsService = $this->sm->get('AmProfile\Service\ProfilePermissionService');

            if (!$permissionsService->hasModuleAccess($module,$action))
            {
                return $this->redirect()->toRoute('administrator',array(
                    'module' => 'login',
                ));
            }
        }

        return parent::onDispatch($e);
    }

    public function setControllerVars()
    {
        $className = get_class($this);

        $tableGateway = preg_replace('/^(Am)(\w+)\\\(\w+)\\\(\w+)(ModuleController)$/', "$1$2\\Model\\\\$2Table", $className);

        if (class_exists($tableGateway)) {
            $this->tableGateway = $this->serviceLocator->get($tableGateway);
        }
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $datatable = $this->sm->get('Administrator\Service\DatatableService');

        $datatable->init();

        return $datatable->run();
    }

    public function addAction()
    {
        $formService = $this->formService;

        $model = $this->tableGateway->getEntityModel();

        $form = $formService
            ->setForm($this->form, $model)
            ->addFields()
            ->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $isValid = $formService->resolveForm($request->getPost());

            if ($isValid) {

                $insertId = $formService->save();

                return $this->goToSection($formService->getRouteParams('module'), array(
                    'action'  => 'edit',
                    'id'      => $insertId[0]
                ));
            }
        }

        $title = "Nuevo";

        return $this->getAddView(compact( 'form', 'title' ));
    }

    public function editAction()
    {
        $thisModule = $this->formService->getRouteParams('module');

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->goToSection($thisModule);
        }

        try {
            $model = $this->tableGateway->find($id);
        } catch (\Exception $ex) {
            return $this->goToSection($thisModule);
        }

        $form = $this->formService->setForm($this->form, $model)->addFields()->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $isValid = $this->formService->resolveForm($request->getPost());

            if ($isValid) {

                $this->formService->save();

                return $this->goToEditSection($thisModule, $id);
            }
        }

        $title = 'Edición';

        return $this->getEditView(compact( 'form', 'title' ));
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
}