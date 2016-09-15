<?php

namespace AmPartner\Controller;

use Administrator\Controller\AuthController;
use Administrator\Traits\AddAction;
use Administrator\Traits\EditAction;
use Administrator\Traits\IndexAction;
use Administrator\Traits\DeleteAction;

class AmPartnerModuleController extends AuthController
{

    use indexAction, addAction, editAction, deleteAction;

    protected $form = 'AmPartner\\Form\\PartnerForm';


}

