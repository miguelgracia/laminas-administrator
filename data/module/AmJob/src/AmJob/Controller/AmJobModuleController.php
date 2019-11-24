<?php

namespace AmJob\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\DeleteAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmJob\Form\JobForm;

class AmJobModuleController extends AuthController
{
    use IndexAction, AddAction, EditAction, DeleteAction;

    protected $form = JobForm::class;
}