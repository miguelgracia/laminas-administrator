<?php

namespace AmProfile\Model;

use Administrator\Model\AdministratorModel;


class ProfileModel extends AdministratorModel
{
    public function getPermissions()
    {
        return json_decode($this->permissions);
    }
}