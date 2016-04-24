<?php

namespace AmConfiguration\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;

class ValoresConfiguracionTable extends AdministratorTable
{
    protected $table = 'valores_configuracion';

    public function getValue($entryKey)
    {
        $rowset = $this->select(array('entry_key' => $entryKey));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("No se pudo encontrar fila con entryKey $entryKey");
        }

        return $row;
    }

    public function saveValoresConfiguracion(AdministratorModel $objetoDatos)
    {
        $entryKey = $objetoDatos->getEntryKey();
        $entryValue = $objetoDatos->getEntryValue();

        $affectedRows = $this->update(array(
            'entry_key'   => $entryKey,
            'entry_value'  => $entryValue,
        ), array(
            // WHERE
            'entry_key' => $entryKey
        ));

        return $affectedRows;
    }

    public function deleteValoresConfiguracion($id)
    {
        $this->delete(array('id' => (int) $id));
    }
}