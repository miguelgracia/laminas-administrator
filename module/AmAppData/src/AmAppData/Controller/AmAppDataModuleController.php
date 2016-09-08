<?php

namespace AmAppData\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;

class AmAppDataModuleController extends AuthController
{
    use indexAction, editAction;

    protected $form = 'AmAppData\\Form\\AppDataForm';

}