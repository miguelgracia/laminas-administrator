<?php

namespace AmProfile\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;

class ProfileTable extends AdministratorTable
{
    protected $table = "gestor_perfiles";

    public function deletePerfil($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}