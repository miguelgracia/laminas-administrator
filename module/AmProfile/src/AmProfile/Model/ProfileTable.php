<?php

namespace AmProfile\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;

class ProfileTable extends AdministratorTable
{
    protected $table = "admin_profiles";

    protected $entityModelName =  ProfileModel::class;

    public function deletePerfil($id)
    {
        $this->delete(array('id' => (int) $id));
    }

    public function find($id, $key = 'id')
    {
        $row = parent::find($id, $key);
        $row->permissions = $row->getPermissions();
        return $row;
    }

    public function save(AdministratorModel $model)
    {
        if (is_array($model->permissions)) {
            $model->permissions = json_encode($model->permissions);
        }

        return parent::save($model);
    }
}