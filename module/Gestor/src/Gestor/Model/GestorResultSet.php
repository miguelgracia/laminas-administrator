<?php

namespace Gestor\Model;

use Zend\Db\ResultSet\HydratingResultSet;

class GestorResultSet extends HydratingResultSet
{
    /**
     * Incluímos todas aquellas propiedades que queremos ocultar al llamar a la función toArray
     * En principio son objetos que no tienen nada que ver con los datos de la propia entidad en sí,
     * por lo que no tiene sentido devolverlos
     *
     * @var array
     */
    protected $hiddenProperties = array(
        'inputFilter',
        'serviceLocator',
        'metadata'
    );

    /**
     * @var $fieldFetchPrimaryColumn
     *
     * Corresponde al nombre del campo de la tabla cuyo valor usaremos
     * para setear la key del array de resultado
     */
    protected $fieldFetchPrimaryColumn;

    /**
     * @var $fieldFetchSecondaryColumn
     *
     * Corresponde al nombre del campo de la tabla cuyo valor usaremos
     * para setear la key del array que se forma dentro de cada grupo
     */

    protected $fieldFetchSecondaryColumn;

    private function removeHiddenProperties(&$row)
    {
        if ($row) {
            foreach ($this->hiddenProperties as $hiddenProperty) {
                if (array_key_exists($hiddenProperty, $row)) {
                    unset($row[$hiddenProperty]);
                }
            }
        }
    }

    public function setFetchGroupResultSet($primaryColumn, $secondaryColumn = null)
    {
        $this->fieldFetchPrimaryColumn = $primaryColumn;
        $this->fieldFetchSecondaryColumn = $secondaryColumn;
    }

    /**
     * Cast result set to array of arrays
     *
     * Sobreescribimos la funcionalidad por defecto para poder setear la key del array
     * de retorno. Si conoces alguna forma nativa de Zend, no dudes en modificar este sistema
     *
     * @return array
     * @throws Exception\RuntimeException if any row is not castable to an array
     */
    public function toArray()
    {
        $return = array();
        foreach ($this as $currentIndex => $row) {

            $currentRow = $this->setCurrentRow($row);

            $this->removeHiddenProperties($currentRow);

            if (!is_null($currentRow)) {
                if (!is_null($this->fieldFetchPrimaryColumn) and isset($currentRow[$this->fieldFetchPrimaryColumn])) {
                    $currentIndex = $currentRow[$this->fieldFetchPrimaryColumn];

                    if (!is_null($this->fieldFetchSecondaryColumn) and isset($currentRow[$this->fieldFetchSecondaryColumn])) {
                        $return[$currentIndex][$currentRow[$this->fieldFetchSecondaryColumn]] = $currentRow;
                    }
                } else {
                    $return[$currentIndex] = $currentRow;
                }
            }
        }
        return $return;
    }

    /**
     * Devuelve los resultados en un array de objetos.
     * @return array
     */
    public function toObjectArray()
    {
        $result = array();
        foreach ($this as $row) {
            $result[] = $row;
        }
        return $result;
    }

    public function toKeyValueArray($key, $value = array())
    {
        $return = array();
        foreach ($this as $row) {
            $currentRow = $this->setCurrentRow($row);
            $this->removeHiddenProperties($currentRow);
            if (is_array($value)) {
                $values = array();
                array_map(function ($elem) use($currentRow, &$values) {
                     $values[] = $currentRow[$elem];
                },$value);
                $stringVal = implode(' ',$values);
            } else {
                $stringVal = $currentRow[$value];
            }

            $return[$currentRow[$key]] = $stringVal;
        }
        return $return;
    }


    private function setCurrentRow($row)
    {
        if (is_array($row)) {
            $currentRow = $row;
        } elseif (method_exists($row, 'toArray')) {
            $currentRow = $row->toArray();
        } elseif (method_exists($row, 'getArrayCopy')) {
            $currentRow = $row->getArrayCopy();
        } else {
            throw new Exception\RuntimeException(
                'Rows as part of this DataSource, with type ' . gettype($row) . ' cannot be cast to an array'
            );
        }

        return $currentRow;
    }
}