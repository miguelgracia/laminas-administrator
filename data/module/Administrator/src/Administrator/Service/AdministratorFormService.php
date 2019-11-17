<?php

namespace Administrator\Service;

use Administrator\Form\AdministratorForm;
use Administrator\Model\AdministratorModel;
use Zend\Db\Metadata\Object\ColumnObject;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

use Zend\Filter\Word\SeparatorToCamelCase;
use Zend\Form\Element\Hidden;
use Zend\Form\Fieldset;

class AdministratorFormService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    protected $formManager;

    /**
     * @var \Zend\Form\Form
     */
    protected $form = null;

    /**
     * @var array
     */
    protected $fieldsets = array();

    /**
     * @var Fieldset
     */
    protected $baseFieldset = null;

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

    /**
     * @var array
     *
     * Array clave / valor.
     * La clave corresponde a los id's que se van asignando a los elementos del formulario.
     * El valor es el número de veces que aparece ese id en el formulario.
     * Si un id se repite, le añadimos un sufijo númerico.
     */
    protected $elementsId = array();

    public function eventTrigger($eventName,  $args = array())
    {
        $args = array('formService' => $this) + $args;
        // En result almacenamos el primer resultado de todos los listener que están escuchando
        // el evento $eventName. En principio el único que va a mandar resultado va a ser
        // CrudListener. Si hubiese algún otro Listener definido, sería para aplicar lógica
        // que no interfiera con el guardado en base de datos.


        $result = $this->getEventManager()->trigger($eventName,null,$args)/*->first()*/;

        return $result;
    }

    /**
     * @param $formElementManager
     */
    public function __construct($formElementManager)
    {
        $this->formManager = $formElementManager;
    }

    public function setPrimaryKey($primaryKey)
    {
        $this->hiddenPrimaryKey = $primaryKey;
    }

    public function getBaseFieldset()
    {
        return $this->baseFieldset;
    }

    public function setBaseFieldset($baseFieldset)
    {
        $this->baseFieldset = $baseFieldset;
    }

    public function setForm($form = null, AdministratorModel $model)
    {
        if ($this->form instanceof AdministratorForm) {
            return $this;
        }

        $this->form = $this->formManager->build($form);

        $formInitializers = $this->form->initializers();

        foreach ($formInitializers['fieldsets'] as $fieldsetName) {
            $this->setFieldset($fieldsetName, $model);
        }

        $form = $this->form;

        $triggerInit = $form->getRouteParams('action') == 'add'
            ? $form::EVENT_CREATE_INIT_FORM
            : $form::EVENT_UPDATE_INIT_FORM;

        $eventResult = $this->eventTrigger($triggerInit);

        return $this;
    }

    private function setFieldset($fieldsetName, $model)
    {
        $isLocale = strpos($fieldsetName, "LocaleFieldset") !== false;

        if (!$isLocale) {
            $fieldset = $this->formManager->build($fieldsetName, [
                'model' => $model,
            ]);
            $this->fieldsets[$fieldset->getName()] = $fieldset;
            return;
        }

        $localeFieldsets = $this->formManager->build($fieldsetName, [
            'base_fieldset' => $this->baseFieldset,
        ]);

        $this->fieldsets += $localeFieldsets;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function getRouteParams($param = false)
    {
        return $this->form->getRouteParams($param);
    }

    public function addFields()
    {
        /**
         * Buscaremos en el objeto formulario y en los objetos Fieldset si existe el método addFields.
         * En caso afirmativo, lo ejecutamos para poder añadir campos adicionales
         * que se salga de la lógica predeterminada o, por ejemplo, redefinir
         * el atributo de algún campo concreto. (Vease Gestor\Form\GestorUsuariosForm)
         */
        $thisMethod = substr(strrchr(__METHOD__, '::'), 1);

        foreach ($this->fieldsets as &$fieldset) {

            $columns = $fieldset->getColumns();

            $hiddenFields = $fieldset->getHiddenFields();

            foreach ($columns as $column) {

                $toCamel = new SeparatorToCamelCase('_');
                $columnName = lcfirst($toCamel->filter($column->getName()));

                if (in_array($columnName, $hiddenFields)) {
                    continue;
                }

                $flags = array(
                    'priority' => -($column->getOrdinalPosition() * 100),
                );

                if (in_array($column->getName(), array(
                    $this->hiddenPrimaryKey,
                    $this->hiddenRelatedKey,
                    'language_id'
                ))) {
                    $element = $this->formManager->build(Hidden::class);
                    $this->setElementConfig($column, $element);
                    $fieldset->add($element, $flags);
                    continue;
                }

                $dataType = $column->getDataType();

                if ($this->formManager->has($columnName)) {
                    $element = $this->formManager->build($columnName);
                } else {
                    $element = $this->formManager->build($dataType);
                }

                $this->setElementConfig($column, $element);

                $fieldset->add($element);
            }

            $fieldset->populateValues($fieldset->getObjectModel()->getArrayCopy());

            if (method_exists($fieldset, $thisMethod)) {
                $fieldset->{$thisMethod}();
            }

            $this->form->add($fieldset);
        }

        if (method_exists($this->form, $thisMethod)) {
            $this->form->{$thisMethod}();
        }

        return $this;
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
            'id'    => $this->checkId($columnName),
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

        return $this;
    }

    /**
     * @param $formElement
     *
     * Comprobamos que los id's que se han asignado a los elementos de formulario no están repetidos
     * Si encontramos un caso en el que sí este, añadimos un prefijo al id
     */
    private function checkId($id)
    {
        if (array_key_exists($id, $this->elementsId)) {
            $this->elementsId[$id]++;
            $id = $id . "_" . $this->elementsId[$id];
        } else {
            $this->elementsId[$id] = 0;
        }

        return $id;
    }

    public function resolveForm($data)
    {
        $form = $this->form;

        $form->bind($data);

        $isValid = true;

        if ($form->isValid()) {
            $this->eventTrigger($form::EVENT_CREATE_VALID_FORM_SUCCESS);
        } else {
            $isValid = false;
            $this->eventTrigger($form::EVENT_CREATE_VALID_FORM_FAILED);
        }

        return $isValid;
    }

    public function save()
    {
        $result = array();

        $baseFieldset = $this->baseFieldset;

        $primaryId = $baseFieldset->getTableGateway()->save($baseFieldset->getObjectModel());

        $result[] = $primaryId;

        unset($this->fieldsets[get_class($baseFieldset)]);

        foreach ($this->fieldsets as $fieldset) {

            $tableGateway   = $fieldset->getTableGateway();
            $model          = $fieldset->getObjectModel();

            $isLocaleFieldset = $fieldset->getOption('is_locale');

            if ($isLocaleFieldset) {

                $model->relatedTableId = $primaryId;
            }

            $resultId = $tableGateway->save($model);
            $result[] = $resultId;
        }

        return $result;
    }
}