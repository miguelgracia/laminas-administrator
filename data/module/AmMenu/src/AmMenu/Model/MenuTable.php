<?php

namespace AmMenu\Model;

use Administrator\Model\AdministratorTable;
use Zend\Db\Sql\Select;

class MenuTable extends AdministratorTable
{
    protected $table = "admin_menus";

    protected $entityModelName =  MenuModel::class;

    public function fetchAllOrdenados()
    {
        $arrayResult = array();

        $selectParent = $this->sql->select()->join(
            'admin_modules',
            'admin_menus.admin_module_id = admin_modules.id',
            'zend_name',
            Select::JOIN_LEFT
        )->order('order ASC');

        //clonamos la consulta padre porque nos servirá para sacar los hijos ya que son los mismos
        //parámetros para las dos consultas (solo varía el where que se lo añadimos después

        $selectChildren = clone $selectParent;

        $selectParent->where(array(
            'admin_menus.parent' => '0'
        ));

        $result = $this->selectWith($selectParent);

        // Sacamos una fila
        foreach($result as $row) {
            // Vamos a sacar sus hijos
            $thisChildren = clone $selectChildren;

            $thisChildren->where(array(
                'admin_menus.parent' => $row->id
            ));

            $row->hijos = $this->selectWith($thisChildren)->toObjectArray();

            $arrayResult[] = $row;
        }

        return $arrayResult;
    }

    public function deleteEntradaMenu($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}