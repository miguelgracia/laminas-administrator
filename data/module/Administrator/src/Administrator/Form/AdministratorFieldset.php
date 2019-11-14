<?php

namespace Administrator\Form;

use Administrator\Filter\SlugFilter;
use Administrator\Filter\MediaUri;
use Administrator\Traits\ServiceLocatorAwareTrait;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Sql\Where;
use Zend\Filter\Word\UnderscoreToCamelCase;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

abstract class AdministratorFieldset extends Fieldset implements InputFilterProviderInterface
{
    use ServiceLocatorAwareTrait;

    protected $tableGateway;

    /**
     * @var bool
     *
     * Lo usaremos para indicar si el fieldset a instanciar se considera el fieldset primario
     * No usamos la funcionalidad que nos proporciona el setear la opcion "use_as_base_fieldset"
     * porque nos da error. De ahí que implementemos nuestro propio sistema.
     *
     * TODO: Intentar averiguar si podemos usar use_as_base_fieldset.
     *
     */
    protected $isPrimaryFieldset = false;

    /**
     * @var \Zend\Db\Metadata\Metadata
     *
     * Nos da acceso a métodos para tratamiento de las tablas de base de datos.
     * Listado de columnas, tipo de dato de las columnas, etc
     */
    protected $metadata;

    protected $table;
    protected $columnsTable;

    protected $objectModel;

    /**
     * @var array
     *
     * Contiene el nombre de aquellos campos que no queremos pintar en la vista
     */
    protected $hiddenFields = array();

    public function init()
    {

    }

    public function setObjectModel($objectModel)
    {
        $this->setObject($objectModel);
        $this->objectModel = $objectModel;
        return $this;
    }

    public function getObjectModel()
    {
        return $this->objectModel;
    }

    public function isPrimaryFieldset()
    {
        return $this->isPrimaryFieldset;
    }

    public function setTableGateway($tableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->table = $tableGateway->getTable();
        return $this;
    }

    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    public function getTableGatewayName()
    {
        return $this->tableGatewayName;
    }

    public function getColumns()
    {
        if (!$this->columnsTable) {
            $this->columnsTable = $this->metadata->getColumns($this->table);
        }
        return $this->columnsTable;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
        return $this;
    }

    public function getInputFilterSpecification()
    {
        $filter = array();

        $dashToCamel = new UnderscoreToCamelCase();

        $columns = $this->getColumns();

        $hiddenFields = $this->getHiddenFields();

        foreach ($columns as $column) {

            $columnName = $column->getName();

            $filterParams = array(
                'filters' => $this->setFilters($column)
            );

            if (in_array($columnName, array('id', 'related_table_id'))) {
                $required = false;
            } else {
                $required = $column->getIsNullable() ? false : true;
                //seteamos los validadores en función del tipo de dato
                $filterParams['validators'] = $this->setValidators($column);
            }

            $name = lcfirst($dashToCamel->filter($columnName));

            //Los campos seteados como ocultos no se validan
            if (in_array($name, $hiddenFields)) {
                continue;
            }

            $filterParams['name'] = $name;
            $filterParams['required'] = $required;

            $filter[$name] = $filterParams;
        }

        return $filter;
    }

    protected function setFilters(ColumnObject $column)
    {
        $filters = array();

        $columnName = $column->getName();
        $dataType = $column->getDataType();

        switch ($columnName) {
            case 'url_key':
            case 'key':
                $filters[] = array(
                    'name' => SlugFilter::class,
                    'options' => array()
                );
                break;
            case 'content':
                $filters[] = array(
                    'name' => MediaUri::class,
                    'options' => array(
                        'relative_path' => '/media/',
                        'html_tags' => array('img','source'),
                    )
                );
                break;
        }

        switch ($dataType) {
            case 'timestamp':
                $filters[] = array(
                    'name' => 'DateSelect'
                );
                break;
        }

        return $filters;
    }

    protected function setValidators(ColumnObject $column)
    {
        $validators = array();

        $dataType = $column->getDataType();
        $columnName = $column->getName();

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
            case 'timestamp':
                $validators[] = array(
                    'name' => 'Zend\Validator\Date',
                    'options' => array(
                        'format' => 'Y-m-d'
                    )
                );
                break;
        }

        /**
         * Los campos que se llamen key o url_key serán tratados como campos únicos.
         */
        if (in_array($columnName, array('key','url_key'))) {

            $isLocale = $this->tableGateway->isLocaleTable();

            if ($isLocale) {
                //buscamos que no exista un segmento igual pero dentro del mismo idioma y que
                // tampoco sea el id que estamos editando (si no, daría error siempre)
                //Dos segmentos pueden ser iguales siempre que su idioma sea distinto.

                $toCamel = new UnderscoreToCamelCase();
                $where = new Where();
                $field = lcfirst($toCamel->filter($columnName));

                $where
                    ->equalTo($columnName, $this->get($field)->getValue())
                    ->and
                    ->equalTo('language_id',$this->get('languageId')->getValue())
                    ->and
                    ->notEqualTo('id',$this->get('id')->getValue());

                $exclude = $where;

            } else {
                $exclude = array(
                    'field' => 'id',
                    'value' => $this->get('id')->getValue()
                );
            }


            $validators[] = array(
                'name' => 'Zend\Validator\Db\NoRecordExists',
                'options' => array(
                    'table' => $this->tableGateway->getTable(),
                    'field' => $columnName,
                    'adapter' => $this->tableGateway->getAdapter(),
                    'exclude' => $exclude
                )
            );
        }

        return $validators;
    }

    /**
     * Esta función se busca en la función addFields de AdministratorFormService
     * Devuelve un array con los elementos del fieldset que no se deben pintar en el formulario
     *
     * @return array
     */

    public function getHiddenFields()
    {
        $this->hiddenFields = array(
            'createdAt', //Cuidado con cambiar el orden de estos elementos!
            'updatedAt',
            'deletedAt'
        );

        return $this->hiddenFields;
    }
}