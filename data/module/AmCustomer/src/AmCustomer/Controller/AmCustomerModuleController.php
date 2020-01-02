<?php

namespace AmCustomer\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use Administrator\Traits\DeleteAction;
use AmCustomer\Form\CustomerForm;

class AmCustomerModuleController extends AuthController
{
    use IndexAction, AddAction, EditAction, DeleteAction;

    public const FORM_CLASS = CustomerForm::class;
}
