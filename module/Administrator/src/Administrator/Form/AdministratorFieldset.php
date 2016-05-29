<?php

namespace Administrator\Form;


use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Metadata\Source\Factory;
use Zend\Filter\Word\UnderscoreToCamelCase;
use Zend\Form\Fieldset;
use Zend\Hydrator\ArraySerializable;
use Zend\InputFilter\InputFilterProviderInterface;

abstract class AdministratorFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $serviceLocator;
    protected $tableGateway;

    protected $metadata;
    protected $table;
    protected $columnsTable;

    protected $objectModel;

    protected $formActionType;

    public function __construct($serviceLocator, $objectModel, $tableGateway)
    {
        $className = get_class($this);
        parent::__construct($className);

        $this->serviceLocator = $serviceLocator;
        $this->tableGateway = $tableGateway;

        $this->table = $tableGateway->getTable();

        $this->metadata = Factory::createSourceFromAdapter($serviceLocator->get('Zend\Db\Adapter\Adapter'));

        $this->setHydrator(new ArraySerializable())
            ->setObject($objectModel);

        $this->objectModel = $objectModel;

        $this->formActionType = $serviceLocator->get('Administrator\Service\AdministratorFormService')->getActionType();
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

        foreach ($columns as $column) {

            $columnName = $column->getName();

            $filterParams = array();

            if ($columnName == 'id') {
                $required = false;
            } else {
                $required = $column->getIsNullable() ? false : true;
                //seteamos los validadores en función del tipo de dato
                $filterParams['validators'] = $this->setValidators($column);
            }

            $name = lcfirst($dashToCamel->filter($columnName));
            $filterParams['name'] = $name;
            $filterParams['required'] = $required;

            $filter[$name] = $filterParams;
        }

        return $filter;
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