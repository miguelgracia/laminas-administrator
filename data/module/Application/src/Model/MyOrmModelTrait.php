<?php

namespace Application\Model;

use Laminas\Filter\Word\CamelCaseToUnderscore;
use Laminas\Filter\Word\UnderscoreToCamelCase;

trait MyOrmModelTrait
{
    public function __call($name, $arguments)
    {
        preg_match('/^(get|set)(.+)/', $name, $output_array);

        if (isset($output_array[1])) {
            switch ($output_array[1]) {
                case 'get':
                    $property = $this->parseProperty($output_array[2]);
                    return property_exists($this, $property) ? $this->{$property} : null;
                    break;
                case 'set':
                    $property = $this->parseProperty($output_array[2]);
                    $this->{$property} = $arguments[0];
                    return $this;
                    break;
            }
        }
    }

    protected function parseProperty($property)
    {
        $toCamelCase = new UnderscoreToCamelCase();
        return lcfirst($toCamelCase->filter($property));
    }

    public function getObjectCopy()
    {
        $newObject = new \stdClass();

        $arrayCopy = $this->getArrayCopy();

        foreach ($arrayCopy as $fieldKey => $value) {
            $newObject->{$fieldKey} = $value;
        }

        return $newObject;
    }

    /**
     * Devuelve un array con los datos del modelo
     *
     * @return array
     */
    public function getArrayCopy()
    {
        $object = get_object_vars($this);

        /*
         * extraemos las propiedades que no hace referencia a datos
         * del modelo. Por ahora son las propiedades que hay
         * declaradas en esta clase:
         *
         * protected $inputFilter;
         * protected $table;
         */

        $classVars = array_keys(get_class_vars(get_class($this)));

        foreach ($classVars as $property) {
            unset($object[$property]);
        }

        return $object;
    }

    /**
     * Es el método que se ejecuta cuando llamados a la función toArray
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

    public function prepareToSave()
    {
        $toSaveArray = [];

        $props = $this->getArrayCopy();

        $toSeparatorFilter = new CamelCaseToUnderscore();

        foreach ($props as $propName => $propValue) {
            $toSaveArray[strtolower($toSeparatorFilter->filter($propName))] = $propValue;
        }

        return $toSaveArray;
    }
}
