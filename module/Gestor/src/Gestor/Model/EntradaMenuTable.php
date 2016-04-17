<?php

namespace Gestor\Model;

use Zend\Db\Sql\Select;

class EntradaMenuTable extends GestorTable
{
    protected $table = "entradamenu";

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function fetchAllOrdenados()
    {
        $arrayResult = array();

        $result = $this->select(function (Select $select){
            $select->join(
                //tabla con la que hacemos join
                'gestorcontrolador',
                //Condición del join
                'entradamenu.idControlador = gestorcontrolador.id',
                //Campos que nos traemos de la tabla del join
                'nombreZend',
                //Tipo de join
                $select::JOIN_LEFT
            )
            ->where(array('entradamenu.padre' => -1))
            ->order('orden ASC');
        });

        // Sacamos una fila
        foreach($result as $row) {

            // Vamos a sacar sus hijos
            $resultInf = $this->select(function(Select $select) use ($row){
                $select->join(
                    'gestorcontrolador',
                    'gestorcontrolador.id = entradamenu.idControlador',
                    'nombreZend',
                    $select::JOIN_LEFT)
                ->where(array('entradamenu.padre' => $row->id))
                ->order('orden ASC');
            });

            // Recorremos todos sus hijos
            $row->cuantosHijos = $resultInf->count();

            $row->hijos = array();

            foreach($resultInf as $rowInf)
            {
                $row->hijos[] = $rowInf;
            }

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
            'texto'         => $entradaMenu->texto,
            'idControlador' => $entradaMenu->idControlador,
            'accion'        => $entradaMenu->accion,
            'tieneEnlace'   => $entradaMenu->tieneEnlace,
        );

        if ($id == 0) {
            //INSERT
            $data['padre'] = $entradaMenu->padre;
            $data['orden'] = $entradaMenu->orden;

        }

        return $this->save($data, $id);
    }

    public function addParent()
    {

    }

    public function deleteEntradaMenu($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}