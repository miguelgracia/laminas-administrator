<?php

namespace AmSection\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use Administrator\Traits\DeleteAction;

class AmSectionModuleController extends AuthController
{
    use IndexAction, AddAction, EditAction, DeleteAction;
    protected $form = 'AmSection\\Form\\SectionForm';
}