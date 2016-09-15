<?php

namespace AmUser\Model;

use Administrator\Model\AdministratorModel;

class UserModel extends AdministratorModel
{
    protected $password2;
    protected $checkPassword;
}