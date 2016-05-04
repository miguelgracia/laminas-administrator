<?php

namespace AmController\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;
use Zend\Db\Sql\Select;

class ControllerTable extends AdministratorTable
{
    protected $table = "gestor_controlador";

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function fetchWithParent($idPerfilPadre)
    {
        $resultSet = $this->select(function(Select $select) use ($idPerfilPadre) {
            $select->join(
                'gestorpermisos',
                'gestorpermisos.idRecurso = gestorcontrolador.id',
                'idPerfil', // Esto son los campos que me traigo
                $select::JOIN_INNER)
                                   ->where(array('gestorpermisos.idTipoRecurso' => '1',
                                           'gestorpermisos.idPerfil' => $idPerfilPadre,
                                        ));
        });

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

    public function saveGestorControlador(AdministratorModel $gestorControlador)
    {
        $data = array(
            'nombreZend' => $gestorControlador->nombreZend,
            'nombreUsable'  => $gestorControlador->nombreUsable,
        );

        $id = (int) $gestorControlador->id;

        return $this->save($data, $id);
    }

    public function deleteGestorControlador($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}