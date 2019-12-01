<?php

namespace Administrator\Traits;

use Administrator\Form\AdministratorForm;

trait AddAction
{
    public function addAction()
    {
        $this->model = $this->tableGateway->getResultSetPrototype()->getObjectPrototype();

        $form = $this->formService->prepareForm($this::FORM_CLASS, AdministratorForm::ACTION_ADD);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $isValid = $this->formService->resolveForm($request->getPost());

            if ($isValid) {
                $insertId = $this->formService->save();
                return $this->goToSection($this->event->getRouteMatch()->getParam('module'), array(
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