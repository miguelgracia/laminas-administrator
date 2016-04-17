<?php

namespace Gestor\Model;

use Zend\Db\Sql\Select;

class LenguajeTable extends GestorTable
{
    protected $table = "lenguaje";

    public function fetchAll()
    {
        //$resultSet = $this->select();
        $resultSet = $this->select(function (Select $select) {
            $select->order('id ASC');
        });
        return $resultSet;
    }

}
