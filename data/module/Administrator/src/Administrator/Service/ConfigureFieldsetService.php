<?php

namespace Administrator\Service;

use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Filter\Word\SeparatorToCamelCase;
use Zend\Form\Element\Hidden;

class ConfigureFieldsetService
{
    /**
     * @var string
     *
     * Nombre por defecto del campo de tabla de base de datos que se usara
     * como valor oculto del formulario para usarlo como condicion en el where
     * de consulta cuando guardamos un formulario de edición
     */
    protected $hiddenPrimaryKey = 'id';

    /**
     * @var string
     *
     * Nombre del campo de la tabla locale que usaremos para relacionar con su tabla maestra
     */
    protected $hiddenRelatedKey = 'related_table_id';

    protected $formElementManager;

    protected $checkIdService;

    public function __construct($formElementManager, $checkIdService)
    {
        $this->formElementManager = $formElementManager;
        $this->checkIdService = $checkIdService;
    }

    public function configure(&$fieldset)
    {
        $columns = $fieldset->getColumns(false);

        foreach ($columns as $columnName => $column) {

            $flags = array(
                'priority' => -($column->getOrdinalPosition() * 100),
            );

            if (in_array($column->getName(), array(
                $this->hiddenPrimaryKey,
                $this->hiddenRelatedKey,
                'language_id'
            ))) {
                $element = $this->formElementManager->build(Hidden::class);
                $this->setElementConfig($column, $element);
                $fieldset->add($element, $flags);
                continue;
            }

            $dataType = $column->getDataType();

            $fieldsetNamespaceName = (new \ReflectionClass($fieldset))->getNamespaceName();

            $formElement = $fieldsetNamespaceName . '\\Element\\' . ucfirst($columnName);

            if ($this->formElementManager->has($formElement)) {
                $elementName = $formElement;
            } elseif($this->formElementManager->has($columnName)) {
                $elementName = $columnName;
            } else {
                $elementName = $dataType;
            }

            $element = $this->formElementManager->build($elementName);

            $this->setElementConfig($column, $element);

            $fieldset->add($element);
        }

        $fieldset->populateValues($fieldset->getObjectModel()->getArrayCopy());

        if (method_exists($fieldset, 'addElements')) {
            $fieldset->addElements();
        }
    }

    private function setElementConfig(ColumnObject $column, &$element)
    {
        $toCamel = new SeparatorToCamelCase('_');
        $columnName = lcfirst($toCamel->filter($column->getName()));

        $dataType = $column->getDataType();

        $fieldClasses =  array(
            'form-control',
            'js-'.$columnName
        );

        $classes = $element->getAttribute('class');
        $options = $element->getOptions();

        if (!is_null($classes)) {
            $fieldClasses[] = $classes;
        }

        $options += array(
            'data_type' => $dataType,
            'label'     => $columnName,
            'label_attributes' => array(
                'class' => 'col-sm-2 control-label'
            ),
            'priority' => -($column->getOrdinalPosition() * 100),
        );

        $attributes = array(
            'id'    => $this->checkIdService->checkId($columnName),
            'class' => implode(' ',$fieldClasses),
        );

        if ($dataType === 'timestamp') {
            $class = 'form-control select-timestamp';
            $options['day_attributes'] = array(
                'class' => $class . ' day'
            );
            $options['month_attributes'] = array(
                'class' => $class . ' month'
            );
            $options['year_attributes'] = array(
                'class' => $class . ' year'
            );
        }

        $element
            ->setName($columnName)
            ->setLabel($columnName)
            ->setOptions($options)
            ->setAttributes($attributes)
        ;
    }
}