<?php

namespace Administrator\Form;


use Administrator\Filter\SlugFilter;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Metadata\Source\Factory;
use Zend\Filter\Word\DashToCamelCase;
use Zend\Filter\Word\UnderscoreToCamelCase;
use Zend\Form\Fieldset;
use Zend\Hydrator\ArraySerializable;
use Zend\InputFilter\InputFilterProviderInterface;

abstract class AdministratorFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $serviceLocator;
    protected $tableGateway;

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

    protected $formActionType;

    /**
     * @var array
     *
     * Contiene el nombre de aquellos campos que no queremos pintar en la vista
     */
    protected $hiddenFields = array();

    public function __construct($serviceLocator, $objectModel)
    {
        $className = get_class($this);

        parent::__construct($className);

        $this->serviceLocator = $serviceLocator;

        /**
         * El nombre del tableGateway lo vamos a extraer a raiz del nombre del Fieldset
         * Por ejemplo: Si estamos instanciando BlogFieldset, buscaremos su correspondiente tableGateway
         * que será BlogTable. Otro ejemplo sería BlogLocaleFieldset y su tableGateway BlogLocaleTable
         * Debemos sustituir el sufijo "Fieldset" por "Table", además del segundo segmento del namespace
         * que pasará a ser "Model" en vez de "Form"
         */

        $tableGateway = preg_replace('/^(Am\w+)\\\(\w+)\\\(\w+)(Fieldset)$/', "$1\\Model\\\\$3Table", $className);

        $this->tableGateway = $serviceLocator->get($tableGateway);

        $this->table = $this->tableGateway->getTable();

        $this->metadata = Factory::createSourceFromAdapter($serviceLocator->get('Zend\Db\Adapter\Adapter'));

        $this->setHydrator(new ArraySerializable());

        $this->formActionType = $serviceLocator->get('Administrator\Service\AdministratorFormService')->getActionType();
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

    public function getTableGateway()
    {
        return $this->tableGateway;
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
                $filters[] = array(
                    'name' => SlugFilter::class,
                    'options' => array()
                );
                break;
            case 'key':
                $filters[] = array(
                    'name' => SlugFilter::class,
                    'options' => array()
                );
                $filters[] = array(
                    'name' => DashToCamelCase::class,
                    'options' => array()
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
            $validators[] = array(
                'name' => 'Zend\Validator\Db\NoRecordExists',
                'options' => array(
                    'table' => $this->tableGateway->getTable(),
                    'field' => $columnName,
                    'adapter' => $this->tableGateway->getAdapter(),
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $this->get('id')->getValue()
                    )
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