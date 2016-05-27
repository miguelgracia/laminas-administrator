<?php

namespace AmModule\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;
use Zend\Db\Sql\Select;

class ModuleTable extends AdministratorTable
{
    protected $table = "gestor_modules";

    public function saveGestorControlador(AdministratorModel $gestorControlador)
    {
        $data = array(
            'nombre_usable'  => $gestorControlador->nombreUsable,
        );

        $id = (int) $gestorControlador->id;

        return $this->save($data, $id);
    }
}