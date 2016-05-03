<?php

namespace Administrator\Model;

use Zend\Db\Metadata\MetadataInterface;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Filter\Word\UnderscoreToCamelCase;
use Zend\InputFilter\InputFilter;

class AdministratorModel
{
    protected $inputFilter;
    protected $metadata;
    protected $table;
    protected $tableInfo;

    function __call($name, $arguments)
    {
        preg_match("/^(get|set)(.+)/", $name, $output_array);

        if (isset($output_array[1])) {
            switch ($output_array[1]) {
                case "get":
                    $property = $this->parseProperty($output_array[2]);
                    return property_exists($this, $property) ? $this->{$property} : null;
                    break;
                case "set":
                    $property = $this->parseProperty($output_array[2]);
                    $this->{$property} = $arguments[0];
                    break;
            }
        }
    }

    // Esto te permite hacer el bind (serializa las vars del objeto)
    public function getArrayCopy()
    {
        $object = get_object_vars($this);

        /*
         * extraemos las propiedades que no hace referencia a datos
         * del modelo. Por ahora son las propiedades que hay
         * declaradas en esta clase:
         *
         * protected $inputFilter;
         * protected $metadata;
         * protected $table;
         */

        $classVars = array_keys(get_class_vars(get_class($this)));

        foreach ($classVars as $property) {
            unset($object[$property]);
        }

        return $object;
    }

    /**
     * Es el m�todo que se ejecuta cuando llamados a la funci�n toArray
     * del Resultset
     *
     * @param $data
     */
    public function exchangeArray($data)
    {
        foreach ($data as $field => &$value) {
            $this->{$field} = $value;
        }
    }


    /**
     * Elimina todas aquellas propiedades creadas en el objeto que no tengan
     * correspondencia en la tabla de base de datos asociada al modelo
     *
     * @return array
     */
    public function toSave()
    {
        $data = $this->getArrayCopy();

        $columnNames = $this->metadata->getColumnNames($this->table);

        foreach ($data as $field => $value) {
            if (!in_array($field, $columnNames)) {
                unset($data[$field]);
            }
        }

        return $data;
    }

    public function setMetadata(MetadataInterface $metadataInterface, $dbTable = false)
    {
        $this->metadata = $metadataInterface;
        $this->table = $dbTable;
    }

    public function getMetatada()
    {
        return $this->metadata;
    }

    public function getInputFilter($sourceTable = null)
    {
        if ($sourceTable === null) {
            $sourceTable = $this->table;
        }

        $columns = $this->metadata->getColumns($sourceTable);

        $inputFilter = $this->inputFilter
            ? $this->inputFilter
            : new InputFilter();

        foreach ($columns as $column) {

            $columnName = $column->getName();

            $filterParams = array();

            if ($columnName == 'id') {
                $required = false;
            } else {
                $required = $column->getIsNullable() ? false : true;
                //seteamos los validadores en funci�n del tipo de dato
                $filterParams['validators'] = $this->setValidators($column);
            }

            $filterParams['name'] = $columnName;
            $filterParams['required'] = $required;

            $inputFilter->add($filterParams);
        }

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }

    protected function parseProperty($property)
    {
        $toCamelCase = new UnderscoreToCamelCase();
        return lcfirst($toCamelCase->filter($property));
    }

    protected function setValidators(ColumnObject $column)
    {
        $validators = array();

        $dataType = $column->getDataType();

        switch ($dataType) {
            case 'int':
                $validators[] = array(
                    'name' => 'Zend\I18n\Validator\IsInt'
                );
                break;
            case 'varchar':
                $validators[] = array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => '1',
                        'max' => $column->getCharacterMaximumLength()
                    )
                );
                break;
        }

        return $validators;
    }
}