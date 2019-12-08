<?php

namespace AmProfile\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmProfile\Form\ProfileForm;

class AmProfileModuleController extends AuthController
{
    use IndexAction, AddAction, EditAction;

    public const FORM_CLASS = ProfileForm::class;

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->goToSection('profile');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->tableGateway->deletePerfil($id);
            }

            // Redirect to list of perfiles
            return $this->goToSection('profile');
        }

        return [
            'id' => $id,
            'perfil' => $this->tableGateway->find($id)
        ];
    }
}
