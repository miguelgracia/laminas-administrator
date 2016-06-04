<?php

namespace Administrator\Traits;


trait EditAction
{
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
}