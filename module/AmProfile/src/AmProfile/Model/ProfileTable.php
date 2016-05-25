<?php

namespace AmProfile\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;

class ProfileTable extends AdministratorTable
{
    protected $table = "gestor_perfiles";

    public function fetchId($id)
    {
        $resultSet = $this->select(array('id' => $id));
        return $resultSet;
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function fetchWhere($where = array())
    {
        return $this->select($where);
    }

    public function getPerfil($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePerfil(AdministratorModel $perfil)
    {
        $data = array(
            'nombre'                    => $perfil->nombre,
            'descripcion'               => $perfil->descripcion,
            'permisos'                  => $perfil->permisos,
            'es_admin'                  => (string) $perfil->esAdmin
        );

        return $this->save($data, (int) $perfil->id);
    }

    public function deletePerfil($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}