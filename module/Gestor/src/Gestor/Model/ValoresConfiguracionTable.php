<?php

namespace Gestor\Model;

class ValoresConfiguracionTable extends GestorTable
{
    protected $table = 'valoresconfiguracion';

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function getValue($which)
    {
        // Este método saca un entryValue de ValoresConfiguracion donde entryKey = $which

        $rowset = $this->select(array('entryKey' => $which));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("No se pudo encontrar fila con entryKey $which");
        }

        return $row;
    }

    // Este método es el que va a hacer update de un determinado valor
    public function saveValoresConfiguracion(GestorModel $objetoDatos)
    {
        $entryKey = $objetoDatos->getEntryKey();
        $entryValue = $objetoDatos->getEntryValue();

        $affectedRows = $this->update(array(
            'entryKey'   => $entryKey,
            'entryValue'  => $entryValue,
        ), array(
            // WHERE
            'entryKey' => $entryKey
        ));

        return $affectedRows;
    }

    public function deleteValoresConfiguracion($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}