<?php

namespace AmMenu\Model;

use Administrator\Model\AdministratorTable;

class MenuTable extends AdministratorTable
{
    protected $table = "gestor_menu";

    public function fetchAllOrdenados()
    {
        $arrayResult = array();

        $selectParent = $this->sql->select();

        $selectParent->join(
            'gestor_modules',
            'gestor_menu.gestor_module_id = gestor_modules.id',
            'nombre_zend',
            $selectParent::JOIN_LEFT
        )->order('orden ASC');

        //clonamos la consulta padre porque nos servirá para sacar los hijos ya que son los mismos
        //parámetros para las dos consultas (solo varía el where que se lo añadimos después

        $selectChildren = clone $selectParent;

        $selectParent->where(array(
            'gestor_menu.padre' => '0'
        ));

        $result = $this->selectWith($selectParent);

        // Sacamos una fila
        foreach($result as $row) {
            // Vamos a sacar sus hijos
            $thisChildren = clone $selectChildren;

            $thisChildren->where(array('gestor_menu.padre' => $row->id));

            $resultInf = $this->selectWith($thisChildren);

            $row->hijos = $resultInf->toObjectArray();

            $arrayResult[] = $row;
        }

        return $arrayResult;
    }

    public function deleteEntradaMenu($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}