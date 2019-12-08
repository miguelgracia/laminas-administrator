<?php

namespace AmLanguage\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmLanguage\Form\LanguageForm;

class AmLanguageModuleController extends AuthController
{
    use indexAction, addAction, editAction;

    public const FORM_CLASS = LanguageForm::class;
}
