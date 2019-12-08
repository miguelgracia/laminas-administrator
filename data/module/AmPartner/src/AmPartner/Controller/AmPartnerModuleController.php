<?php

namespace AmPartner\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use Administrator\Traits\DeleteAction;
use AmPartner\Form\PartnerForm;

class AmPartnerModuleController extends AuthController
{
    use indexAction, addAction, editAction, deleteAction;

    public const FORM_CLASS = PartnerForm::class;
}
