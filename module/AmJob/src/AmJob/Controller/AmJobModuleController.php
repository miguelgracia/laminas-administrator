<?php

namespace AmJob\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;

class AmJobModuleController extends AuthController
{

    use indexAction, addAction, editAction;

    protected $form = 'AmJob\\Form\\JobForm';


}

