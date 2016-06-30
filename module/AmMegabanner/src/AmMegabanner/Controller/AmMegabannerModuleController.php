<?php

namespace AmMegabanner\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\DeleteAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;

class AmMegabannerModuleController extends AuthController
{

    use indexAction, addAction, editAction, DeleteAction;

    protected $form = 'AmMegabanner\\Form\\MegabannerForm';


}

