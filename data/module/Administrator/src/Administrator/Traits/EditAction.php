<?php

namespace Administrator\Traits;

use AmProfile\Service\ProfilePermissionService;

trait EditAction
{
    public function editAction()
    {
        $thisModule =  $this->serviceLocator->get('Application')->getMvcEvent()->getRouteMatch()->getParam('module');

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->goToSection($thisModule);
        }

        try {
            $this->model = $this->tableGateway->find($id);
        } catch (\Exception $ex) {
            return $this->goToSection($thisModule);
        }

        $form = $this->formService->prepareForm($this::FORM_CLASS);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $isValid = $this->formService->resolveForm($request->getPost());

            if ($isValid) {
                $this->formService->save();
                return $this->goToEditSection($thisModule, $id);
            }
        }

        $title = 'Edición';

        $blocks = $this->parseTriggers();

        $viewParams = compact( 'form', 'title', 'blocks');

        $addAction = 'add';

        $module = $this->getEvent()->getRouteMatch()->getParam('module');

        $permissions = $this->serviceLocator->get(ProfilePermissionService::class);
        if ($permissions->hasModuleAccess($module, $addAction)) {
            $controller = $this->getPluginManager()->getController();

            if (method_exists($controller, $addAction .'Action')) {
                $viewParams['add_action'] = $controller->goToSection($module, array('action' => $addAction), true);
            }
        }

        return $this->getEditView($viewParams);
    }
}