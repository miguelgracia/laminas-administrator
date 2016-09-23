<?php

namespace Administrator\Traits;


trait EditAction
{
    public function editAction()
    {
        $serviceLocator = $this->serviceLocator;

        $formService = $serviceLocator->get('Administrator\Service\AdministratorFormService');

        $application = $serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();

        $thisModule =  $routeMatch->getParam('module');

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->goToSection($thisModule);
        }

        try {
            $model = $this->tableGateway->find($id);
        } catch (\Exception $ex) {
            return $this->goToSection($thisModule);
        }

        $form = $formService->setForm($this->form, $model)->addFields()->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $isValid = $formService->resolveForm($request->getPost());

            if ($isValid) {
                $formService->save();
                return $this->goToEditSection($thisModule, $id);
            }
        }

        $title = 'Edición';

        $blocks = $this->parseTriggers();

        $viewParams = compact( 'form', 'title', 'blocks');

        $addAction = 'add';

        $module = $this->getEvent()->getRouteMatch()->getParam('module');

        $permissions = $this->serviceLocator->get('AmProfile\Service\ProfilePermissionService');
        if ($permissions->hasModuleAccess($module, $addAction)) {
            $controller = $this->getPluginManager()->getController();

            if (method_exists($controller, $addAction .'Action')) {
                $viewParams['add_action'] = $controller->goToSection($module, array('action' => $addAction), true);
            }
        }

        return $this->getEditView($viewParams);
    }
}