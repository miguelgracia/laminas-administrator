<?php

namespace AmJobVideo\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use AmJobVideo\Form\JobVideoForm;

class AmJobVideoModuleController extends AuthController
{
    use indexAction, addAction, editAction;

    public const FORM_CLASS = JobVideoForm::class;
}

