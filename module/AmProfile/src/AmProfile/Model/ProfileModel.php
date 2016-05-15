<?php

namespace AmProfile\Model;

use Administrator\Model\AdministratorModel;


class ProfileModel extends AdministratorModel
{
    public function getPermisos()
    {
        return json_decode($this->permisos);
    }
}