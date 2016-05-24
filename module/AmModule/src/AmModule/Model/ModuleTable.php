<?php

namespace AmModule\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;
use Zend\Db\Sql\Select;

class ModuleTable extends AdministratorTable
{
    protected $table = "gestor_modules";

    public function fetchAll()
    {
        $resultSet = $this->select();
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