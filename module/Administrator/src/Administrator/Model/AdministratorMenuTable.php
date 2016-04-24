<?php

namespace Administrator\Model;

use Zend\Db\Sql\Select;

class AdministratorMenuTable extends AdministratorTable
{
    protected $table = "gestor_menu";

    public function fetchAllOrdenados()
    {
        $arrayResult = array();

        $selectParent = $this->sql->select();

        $selectParent->join(
            'gestor_controlador',
            'gestor_menu.gestor_controlador_id = gestor_controlador.id',
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

    public function saveEntradaMenu(GestorModel $entradaMenu)
    {
        $id = (int) $entradaMenu->id;

        $data = array(
            'texto'                 => $entradaMenu->texto,
            'gestor_controlador_id' => $entradaMenu->gestorControladorId,
            'accion'                => $entradaMenu->accion,
            'tiene_enlace'          => $entradaMenu->tieneEnlace,
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