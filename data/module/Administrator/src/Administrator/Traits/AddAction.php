<?php

namespace Administrator\Traits;

trait AddAction
{
    public function addAction()
    {
        $this->model = $this->tableGateway->getResultSetPrototype()->getObjectPrototype();

        $form = $this->formService->prepareForm($this::FORM_CLASS);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $isValid = $this->formService->resolveForm($request->getPost());

            if ($isValid) {
                $insertId = $this->formService->save();
                return $this->goToSection($this->formService->getRouteParams('module'), array(
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