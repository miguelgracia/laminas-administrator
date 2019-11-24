<?php

namespace Administrator\Traits;


trait AddAction
{
    public function addAction()
    {
        $formService = $this->serviceLocator->get('Administrator\Service\AdministratorFormService');

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

        $blocks = $this->parseTriggers();

        return $this->getAddView(compact( 'form', 'title', 'blocks'));
    }
}