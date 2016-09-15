<?php

namespace AmJobCategory\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\DeleteAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;

class AmJobCategoryModuleController extends AuthController
{
    use IndexAction, AddAction, EditAction, DeleteAction;

    protected $form = 'AmJobCategory\\Form\\JobCategoryForm';
}