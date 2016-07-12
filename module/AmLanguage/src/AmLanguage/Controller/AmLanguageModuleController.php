<?php

namespace AmLanguage\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;

class AmLanguageModuleController extends AuthController
{
    use indexAction, addAction, editAction;

    protected $form = 'AmLanguage\\Form\\LanguageForm';
}