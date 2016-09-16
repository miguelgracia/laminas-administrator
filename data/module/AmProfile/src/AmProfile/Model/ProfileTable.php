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

    public function save($data, $id = 0, $fieldKey = 'id')
    {
        if (is_array($data->permissions)) {
            $data->permissions = json_encode($data->permissions);
        }

        return parent::save($data, $id, $fieldKey);
    }
}