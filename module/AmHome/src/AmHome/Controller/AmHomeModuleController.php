<?php

namespace AmHome\Controller;

use Administrator\Controller\AuthController;

class AmHomeModuleController extends AuthController
{
    protected $userTable;
    public function setControllerVars()
    {
        $this->userTable = $this->sm->get('AmUser\Model\UserTable');
    }

    public function indexAction()
    {

    }
}