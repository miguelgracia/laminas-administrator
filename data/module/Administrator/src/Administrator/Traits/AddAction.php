<?php

namespace Administrator\Traits;


use Administrator\Service\AdministratorFormService;

trait AddAction
{
    public function addAction()
    {
        $formService = $this->serviceLocator->get(AdministratorFormService::class);

        $model = $this->tableGateway->getEntityModel();

        $form = $formService->prepareForm($this->form, $model);

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