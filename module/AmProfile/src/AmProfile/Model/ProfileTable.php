<?php

namespace AmProfile\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;

class ProfileTable extends AdministratorTable
{
    protected $table = "gestor_perfiles";

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