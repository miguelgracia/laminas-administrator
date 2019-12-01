<?php

namespace AmHomeModule\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\DeleteAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmHomeModule\Form\HomeModuleForm;

class AmHomeModuleModuleController extends AuthController
{
    use IndexAction, AddAction, EditAction, DeleteAction;

    public const FORM_CLASS = HomeModuleForm::class;
}