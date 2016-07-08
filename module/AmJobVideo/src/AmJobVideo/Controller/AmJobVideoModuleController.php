<?php

namespace AmJobVideo\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;

class AmJobVideoModuleController extends AuthController
{

    use indexAction, addAction, editAction;

    protected $form = 'AmJobVideo\\Form\\JobVideoForm';


}

