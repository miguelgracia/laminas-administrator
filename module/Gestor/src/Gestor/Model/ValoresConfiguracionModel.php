<?php

namespace Gestor\Model;

use \Zend\Filter\StripTags;

class ValoresConfiguracionModel extends GestorModel
{
    public function setEntryValue($value) {
        if (!is_array($value)) {
            // Filtro que elimina los tags html
            $filter = new StripTags();
            $value = $filter->filter($value);
        }
        $this->entryValue = $value;
    }

    public function getEntryValue() {
        if (is_array($this->entryValue)) {
            $this->entryValue = $this->entryValue['tmp_name'];
        }
        return $this->entryValue;
    }
}