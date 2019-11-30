<?php

namespace Administrator\Traits;

trait AddAction
{
    public function addAction()
    {
        $model = $this->tableGateway->getEntityModel();

        $form = $this->formService->prepareForm($this->form, $model);

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