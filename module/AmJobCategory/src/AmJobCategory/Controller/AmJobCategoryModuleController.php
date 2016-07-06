<?php

namespace AmJobCategory\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;

class AmJobCategoryModuleController extends AuthController
{

    use indexAction, addAction, editAction;

    protected $form = 'AmJobCategory\\Form\\JobCategoryForm';


}

