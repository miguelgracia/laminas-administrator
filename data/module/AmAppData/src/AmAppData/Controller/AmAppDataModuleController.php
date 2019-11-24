<?php

namespace AmAppData\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmAppData\Form\AppDataForm;

class AmAppDataModuleController extends AuthController
{
    use indexAction, editAction;

    protected $form = AppDataForm::class;

    public function indexAction()
    {
        $this->redirect()->toRoute('administrator',array(
            'module' => 'app-data',
            'action' => 'edit',
            'id' => 1
        ));
    }

}