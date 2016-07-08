<?php
namespace AmModule\Controller;

use Administrator\Controller\AuthController;

use Administrator\Traits\DeleteAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmModule\Form\ModuleFieldset;
use AmModule\Form\ModuleForm;

class AmModuleModuleController extends AuthController
{
    use IndexAction, EditAction;

    protected $form = ModuleForm::class;
}