<?php

namespace AmStaticPage\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use Administrator\Traits\DeleteAction;

class AmStaticPageModuleController extends AuthController
{
    use indexAction, addAction, editAction, DeleteAction;

    protected $form = 'AmStaticPage\\Form\\StaticPageForm';
}