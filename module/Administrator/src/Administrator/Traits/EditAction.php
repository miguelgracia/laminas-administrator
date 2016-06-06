<?php

namespace Administrator\Traits;


trait EditAction
{
    public function editAction()
    {
        $formService = $this->serviceLocator->get('Administrator\Service\AdministratorFormService');
        $thisModule = $formService->getRouteParams('module');

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

        return $this->getEditView(compact( 'form', 'title', 'blocks' ));
    }
}