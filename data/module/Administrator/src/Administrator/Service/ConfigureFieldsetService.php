<?php

namespace Administrator\Service;

use Laminas\Db\Metadata\Object\ColumnObject;
use Laminas\Filter\Word\SeparatorToCamelCase;
use Laminas\Form\Element\Hidden;

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
            $flags = [
                'priority' => -($column->getOrdinalPosition() * 100),
            ];

            $reflectionClass = (new \ReflectionClass($fieldset));

            $fieldsetNamespaceName = $reflectionClass->getNamespaceName();

            $moduleNamespace = (explode("\\", $fieldsetNamespaceName))[0];

            if (in_array($column->getName(), [
                $this->hiddenPrimaryKey,
                $this->hiddenRelatedKey,
                'language_id'
            ])) {
                $element = $this->formElementManager->build(Hidden::class);
                $this->setElementConfig($column, $element, $moduleNamespace);
                $fieldset->add($element, $flags);
                continue;
            }

            $dataType = $column->getDataType();

            $formElement = $fieldsetNamespaceName . '\\Element\\' . ucfirst($columnName);

            if ($this->formElementManager->has($formElement)) {
                $elementName = $formElement;
            } elseif ($this->formElementManager->has($columnName)) {
                $elementName = $columnName;
            } else {
                $elementName = $dataType;
            }

            $element = $this->formElementManager->build($elementName);

            $this->setElementConfig($column, $element, $moduleNamespace);

            $fieldset->add($element);
        }

        $fieldset->populateValues($fieldset->getObjectModel()->getArrayCopy());

        if (method_exists($fieldset, 'addElements')) {
            $fieldset->addElements();
        }
    }

    private function setElementConfig(ColumnObject $column, &$element, $moduleNamespace)
    {
        $toCamel = new SeparatorToCamelCase('_');
        $columnName = lcfirst($toCamel->filter($column->getName()));

        $dataType = $column->getDataType();

        $fieldClasses = [
            'form-control',
            'js-' . $columnName
        ];

        $classes = $element->getAttribute('class');
        $options = $element->getOptions();

        if (!is_null($classes)) {
            $fieldClasses[] = $classes;
        }

        $options += [
            'data_type' => $dataType,
            'label' => "$moduleNamespace.$columnName",
            'label_attributes' => [
                'class' => 'col-sm-2 control-label'
            ],
            'priority' => -($column->getOrdinalPosition() * 100),
        ];

        $attributes = [
            'id' => $this->checkIdService->checkId($columnName),
            'class' => implode(' ', $fieldClasses),
        ];

        if ($dataType === 'timestamp') {
            $class = 'form-control select-timestamp';
            $options['day_attributes'] = [
                'class' => $class . ' day'
            ];
            $options['month_attributes'] = [
                'class' => $class . ' month'
            ];
            $options['year_attributes'] = [
                'class' => $class . ' year'
            ];
        }

        $element
            ->setName($columnName)
            ->setLabel($columnName)
            ->setOptions($options)
            ->setAttributes($attributes);
    }
}
