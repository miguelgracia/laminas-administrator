<?php

namespace Administrator\Service;

use Administrator\Model\AdministratorModel;
use Administrator\Traits\ServiceLocatorAwareTrait;
use Zend\Db\Metadata\Object\ColumnObject;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

use Zend\Filter\Word\SeparatorToCamelCase;
use Zend\Form\Fieldset;

use Zend\ServiceManager\ServiceLocatorInterface;

class AdministratorFormService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait, ServiceLocatorAwareTrait;

    protected $formManager;

    /**
     * @var \Zend\Form\Form
     */
    protected $form;

    /**
     * @var array
     */
    protected $fieldsets = array();

    /**
     * @var Fieldset
     */
    protected $baseFieldset = null;

    /**
     * @var AdministratorModel
     *
     * Variable que almacenará los datos del fieldset base
     */
    protected $baseModel = null;


    /**
     * @var array
     *
     * Array asociativo que contendrá un conjunto de registros clave/valor para rellenar
     * aquellos campos de tipo select.
     *
     * Ejemplo:
     *
     * $fieldValueOptions = array(
     *      'idPerfil' => array(
     *          '1' => 'Admin',
     *          '2' => 'User',
     *          '3' => 'Guest'
     *       )
     *  );
     */
    protected $fieldValueOptions = array();

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
     * Este array incluye aquellos campos de base de datos que queremos redefinir su tipo.
     * Por ejemplo. idPerfil en base de datos se guarda como tipo entero. Los tipos enteros
     * se reflejan en el formulario como un campo input pero en nuestro caso queremos
     * que sea un Select. Es aquí donde quedará almacenado dicho cambio. Los valores deben corresponder a
     * elementos de tipo Form\Element. Se setea desde el initializer de los formularios
     */
    protected $fieldModifiers = array();

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
     * @param ServiceLocatorInterface $serviceLocator
     * @return $this
     */
    public function __construct($container, $formElementManager)
    {
        $this->serviceLocator = $container;
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
        if (!$this->form) {
            $this->baseModel = $model;

            $this->form = $this->formManager->build($form);

            $this->initializers($this->form);

            $form = $this->form;

            $triggerInit = $form->getRouteParams('action') == 'add'
                ? $form::EVENT_CREATE_INIT_FORM
                : $form::EVENT_UPDATE_INIT_FORM;

            $eventResult = $this->eventTrigger($triggerInit);
        }

        return $this;
    }

    public function initializers($instance)
    {
        if (method_exists($instance, 'initializers')) {
            $initializers = $instance->initializers();

            foreach ($initializers as $property => $initializer) {

                foreach ($initializer as $field => $value) {

                    $method = 'set' . ucfirst($property);

                    if (method_exists($this, $method)) {
                        call_user_func_array(array($this,$method), array(
                            $field,
                            is_callable($value) ? $value() : $value
                        ));
                    } else {
                        throw new \Exception('Method ' . $method . ' not exists in '.get_class($this));
                    }
                }
            }
        }
        return $this;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function getRouteParams($param = false)
    {
        return $this->form->getRouteParams($param);
    }

    /**
     * Setea los valores con los que vamos a rellenar los campos de tipo Select o Multicheckbox
     *
     * @param $fieldName
     * @param array $valueOptions
     */
    public function setFieldValueOptions($fieldName, $valueOptions = array())
    {
        $this->fieldValueOptions[$fieldName] = $valueOptions;
    }

    public function setFieldModifiers($modifier, $value)
    {
        $this->fieldModifiers[$modifier] = $value;
    }

    public function setFieldsets($fieldset, $options = array())
    {
        $isLocale = strpos($fieldset, "LocaleFieldset") !== false;

        if (!$isLocale) {
            $fieldset = $this->formManager->build($fieldset, [
                'model' => $this->baseModel,
            ]);

            $this->initializers($fieldset);
            $this->fieldsets[$fieldset->getName()] = $fieldset;
            return;
        }

        $baseTableGateway = $this->baseFieldset->getTableGateway();
        $objectModel = $this->baseFieldset->getObjectModel();

        $primaryId = isset($objectModel->id) ? $objectModel->id : 0;

        $localeTableGatewayName = preg_replace("/(Table)$/", "LocaleTable", get_class($baseTableGateway));

        $localeTableGateway = $this->serviceLocator->get($localeTableGatewayName);

        $localeModels = $localeTableGateway->findLocales($primaryId);

        foreach ($localeModels as $localModel) {
            $localModel->relatedTableId = $primaryId;
            $localeFieldset = $this->formManager->build($fieldset, [
                'model' => $localModel
            ]);

            $this->initializers($localeFieldset);
            $this->fieldsets[$localeFieldset->getName()] = $localeFieldset;
        }
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

                $fieldParams = $this->setFieldParams($column);

                if (in_array($column->getName(), array(
                    $this->hiddenPrimaryKey,
                    $this->hiddenRelatedKey,
                    'language_id'
                ))) {
                    $fieldParams['type'] = 'Hidden';
                    $fieldset->add($fieldParams, $flags);
                    continue;
                }

                $dataType = $column->getDataType();

                $type = $this->setFormDataType($columnName, $dataType);

                if ($type == 'Select' or $type == 'MultiCheckbox') {

                    if ($dataType == 'enum' and !isset($this->fieldValueOptions[$columnName])) {
                        $enumValues = $column->getErratas();
                        $fieldParams['options']['value_options'] = $enumValues['permitted_values'];
                    } else {
                        $fieldParams['options']['value_options'] = $this->fieldValueOptions[$columnName];
                    }
                }

                $fieldParams['type'] = $type;

                $fieldset->add($fieldParams,$flags);

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

    private function setFieldParams(ColumnObject $column)
    {
        $toCamel = new SeparatorToCamelCase('_');
        $columnName = lcfirst($toCamel->filter($column->getName()));

        $dataType = $column->getDataType();

        $fieldClasses =  array(
            'form-control',
            'js-'.$columnName
        );

        $options = array(
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

        switch ($dataType) {
            case 'timestamp':
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

                break;
        }

        $fieldParams = array(
            'name'       => $columnName,
            'label'      => $columnName,
            'options'    => $options,
            'attributes' => $attributes
        );

        return $fieldParams;
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

    private function setFormDataType($columnName, $dataType)
    {
        if (array_key_exists($columnName, $this->fieldModifiers)) {
            return $this->fieldModifiers[$columnName];
        }

        $fieldType = 'text';

        // Conforme vayan surgiendo posibles valores de $dataType, el siguiente switch
        // ira creciendo

        switch ($dataType) {
            case 'int':
            case 'varchar':
            case 'tinyint':
                $fieldType = 'text';
                break;
            case 'enum':
                $fieldType = 'Select';
                break;
            case 'timestamp':
                $fieldType =  'DateSelect';
                break;
            default:

        }

        return $fieldType;
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