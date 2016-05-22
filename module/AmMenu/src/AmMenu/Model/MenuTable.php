<?php

namespace AmMenu\Model;

use Administrator\Model\AdministratorModel;
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

        //clonamos la consulta padre porque nos servir� para sacar los hijos ya que son los mismos
        //par�metros para las dos consultas (solo var�a el where que se lo a�adimos despu�s

        $selectChildren = clone $selectParent;

        $selectParent->where(array(
            'gestor_menu.padre' => '-1'
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

    public function getEntradaMenu($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveEntradaMenu(AdministratorModel $entradaMenu)
    {
        $id = (int) $entradaMenu->id;

        $data = array(
            'texto'                 => $entradaMenu->texto,
            'gestor_module_id'      => $entradaMenu->gestorModuleId,
            'accion'                => $entradaMenu->accion,
        );

        if ($id == 0) {
            //INSERT
            $data['padre'] = $entradaMenu->padre;
            $data['orden'] = $entradaMenu->orden;

        }

        return $this->save($data, $id);
    }

    public function deleteEntradaMenu($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}