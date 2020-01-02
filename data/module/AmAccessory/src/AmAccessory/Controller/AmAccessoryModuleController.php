<?php

namespace AmAccessory\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\DeleteAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmAccessory\Form\AccessoryForm;

class AmAccessoryModuleController extends AuthController
{
    use IndexAction, AddAction, EditAction, DeleteAction;

    public const FORM_CLASS = AccessoryForm::class;
}
