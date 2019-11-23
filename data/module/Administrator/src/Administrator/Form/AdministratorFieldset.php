<?php

namespace Administrator\Form;

use Administrator\Filter\SlugFilter;
use Administrator\Filter\MediaUri;
use Administrator\Traits\ServiceLocatorAwareTrait;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Sql\Where;
use Zend\Filter\Word\UnderscoreToCamelCase;
use Zend\Form\Fieldset;
use Zend\I18n\Validator\IsInt;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\Between;
use Zend\Validator\Date;
use Zend\Validator\StringLength;

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
     * @var ColumnObject[]
     */
    protected $columnsTable;


    protected $objectModel;

    /**
     * @var array
     *
     * Contiene el nombre de aquellos campos que no queremos pintar en la vista
     */
    protected $hiddenFields = array();

    /**
     * @var \Zend\InputFilter\Factory
     */
    private $inputFilterFactory;

    /**
     * @var \Zend\Filter\FilterPluginManager
     */
    private $filterManager;

    /**
     * @var \Zend\Validator\ValidatorPluginManager
     */
    private $validatorManager;

    public function init()
    {
        $this->inputFilterFactory = $this->factory->getInputFilterFactory();
        $this->validatorManager = $this->inputFilterFactory->getDefaultValidatorChain()->getPluginManager();
        $this->filterManager = $this->inputFilterFactory->getDefaultFilterChain()->getPluginManager();
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

    public function getColumns($includeHiddenColumns = true)
    {
        $dashToCamel = new UnderscoreToCamelCase();

        $columns = [];

        foreach ($this->columnsTable as $key => $column) {
            $columnName = lcfirst($dashToCamel->filter($column->getName()));
            $columns[$columnName] = $column;
        }

        if ($includeHiddenColumns) {
            return $columns;
        }

        $hiddenFields = $this->getHiddenFields();

        foreach ($hiddenFields as $hiddenField) {
            unset($columns[$hiddenField]);
        }

        return $columns;
    }

    public function setColumns($columnsTable)
    {
        $this->columnsTable = $columnsTable;
        return $this;
    }

    public function getInputFilterSpecification()
    {
        $filter = array();

        $columns = $this->getColumns(false);

        foreach ($columns as $columnName => $column) {

            $filter[$columnName] = [
                'name' => $columnName,
                'required' => $this->isColumnRequired($column),
                'filters' => $this->getFilterSpecs($column),
                'validators' => $this->getValidatorSpecs($column)
            ];
        }

        return $filter;
    }

    private function isColumnRequired($column)
    {
        return ($this->isRelationalField($column->getName()) or $column->getIsNullable()) ? false : true;
    }

    /**
     * Comprueba si el campo hace referencia al id de la tabla principal o al campo related_table_id de la tabla
     * locale que corresponda
     * @param $name
     * @return bool
     */
    private function isRelationalField($name)
    {
        return in_array($name, array('id', 'related_table_id'));
    }

    protected function getFilterSpecs(ColumnObject $column)
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

    protected function getValidatorSpecs(ColumnObject $column)
    {
        $validators = array();

        if ($this->isRelationalField($column->getName())) {
            return $validators;
        }

        $dataType = $column->getDataType();
        $columnName = $column->getName();

        switch ($dataType) {
            case 'tinyint':
                $validators[] = array(
                    'name' => Between::class,
                    'options' => [
                        'min' => 0,
                        'max' => 127
                    ]
                );
            case 'int':
                $validators[] = array(
                    'name' => IsInt::class
                );
                break;
            case 'varchar':
                $validators[] = array(
                    'name' => StringLength::class,
                    'options' => array(
                        'min' => '1',
                        'max' => $column->getCharacterMaximumLength()
                    )
                );
                break;
            case 'timestamp':
                $validators[] = array(
                    'name' => Date::class,
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