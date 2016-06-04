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

    public function find($id, $key = 'id')
    {
        $row = parent::find($id, $key);
        $row->permisos = $row->getPermisos();
        return $row;
    }

    public function save(AdministratorModel $model)
    {
        if (is_array($model->permisos)) {
            $model->permisos = json_encode($model->permisos);
        }

        return parent::save($model);
    }
}