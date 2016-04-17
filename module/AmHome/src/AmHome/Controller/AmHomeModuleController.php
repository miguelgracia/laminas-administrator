<?php

namespace AmHome\Controller;

use Administrator\Controller\AuthController;

class AmHomeModuleController extends AuthController
{
    protected $userTable;
    public function setControllerVars()
    {
        $this->userTable = $this->sm->get('Administrator\Model\AdminUserTable');
    }

    public function indexAction()
    {

    }

    public function addAction()
    {
        echo "<pre>";
        print_r('ADD');
        echo "</pre>";

    }
}