<?php

namespace Gestor\Model;

use Zend\Db\Sql\Select;

class ProvinciaTable extends GestorTable
{
    protected $table = "provincia";

    public function fetchAll()
    {
        //$resultSet = $this->select();
        $resultSet = $this->select(function (Select $select) {
            $select->order('nombre ASC');
        });
        return $resultSet;
    }

}
