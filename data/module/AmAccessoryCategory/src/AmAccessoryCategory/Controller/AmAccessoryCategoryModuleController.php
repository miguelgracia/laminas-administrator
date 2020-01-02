<?php

namespace AmAccessoryCategory\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\DeleteAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmAccessoryCategory\Form\AccessoryCategoryForm;

class AmAccessoryCategoryModuleController extends AuthController
{
    use IndexAction, AddAction, EditAction, DeleteAction;

    public const FORM_CLASS = AccessoryCategoryForm::class;
}
