<?php

namespace Administrator\Model;

use Zend\Db\Sql\Select;

class GestorControladorTable extends AdministratorTable
{
    protected $table = "gestor_controlador";

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function fetchWithParent($gestorPerfilIdPadre = '')
    {
        if ($gestorPerfilIdPadre == '') {
            $resultSet = $this->select();
        } else {
            $resultSet = $this->select(function(Select $select) use ($gestorPerfilIdPadre) {
                $select->join(
                    'gestor_permisos',
                    'gestor_permisos.gestor_controlador_id = gestor_controlador.id',
                    'gestor_perfil_id',
                    $select::JOIN_INNER)
                    ->where(array(
                        'gestor_permisos.gestor_perfil_id' => $gestorPerfilIdPadre,
                    ));
            });
        }

        return $resultSet;
    }

    public function getGestorControlador($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveGestorControlador(GestorModel $gestorControlador)
    {
        $data = array(
            'nombre_zend' => $gestorControlador->nombreZend,
            'nombre_usable'  => $gestorControlador->nombreUsable,
        );

        $id = (int) $gestorControlador->id;

        return $this->save($data, $id);
    }

    public function deleteGestorControlador($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}