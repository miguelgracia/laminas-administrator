<?php

namespace AmStaticPage\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use Administrator\Traits\DeleteAction;
use AmStaticPage\Form\StaticPageForm;

class AmStaticPageModuleController extends AuthController
{
    use indexAction, addAction, editAction, DeleteAction;

    public const FORM_CLASS = StaticPageForm::class;
}