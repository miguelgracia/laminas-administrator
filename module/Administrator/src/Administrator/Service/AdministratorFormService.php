<?php

namespace Administrator\Service;

use Administrator\Model\AdministratorModel;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\Filter\Word\SeparatorToCamelCase;
use Zend\Filter\Word\SeparatorToSeparator;
use Zend\Form\Fieldset;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdministratorFormService implements FactoryInterface, EventManagerAwareInterface
{
    protected $eventManager;

    const ACTION_DEFAULT    = 'index';
    const ACTION_ADD        = 'add';
    const ACTION_EDIT       = 'edit';
    const ACTION_DELETE     = 'delete';


    /**
     * Constantes de eventos
     */
    const EVENT_READ        = 'read';

    const EVENT_CREATE                      = 'create';
    const EVENT_CREATE_INIT_FORM            = 'create.init.form';
    const EVENT_CREATE_VALID_FORM_SUCCESS   = 'create.form.valid.success';
    const EVENT_CREATE_VALID_FORM_FAILED    = 'create.form.valid.failed';
    const EVENT_CREATE_SAVE_FORM            = 'create.form.save';

    const EVENT_UPDATE                      = 'update';
    const EVENT_UPDATE_INIT_FORM            = 'update.init.form';
    const EVENT_UPDATE_VALID_FORM_SUCCESS   = 'update.form.valid.success';
    const EVENT_UPDATE_VALID_FORM_FAILED    = 'update.form.valid.failed';
    const EVENT_UPDATE_SAVE_FORM            = 'update.form.save';

    const EVENT_DELETE      = 'delete';

    /**
     * @var array
     *
     * Tipos de action de formulario permitidos y su correspondencia con eventos
     */
    protected $allowedActionType = array(
        self::ACTION_ADD        => self::EVENT_CREATE,
        self::ACTION_DEFAULT    => self::EVENT_READ,
        self::ACTION_EDIT       => self::EVENT_UPDATE,
        self::ACTION_DELETE     => self::EVENT_DELETE,
    );

    protected $defaultAttributes = array(
        'class' => 'form-horizontal'
    );

    protected $actionType = self::ACTION_ADD;

    /**
     * @var
     *
     * Objeto para acceso a los distintos servicios
     */
    protected $serviceLocator;

    /**
     * @var \Zend\Form\Form
     */
    protected $form;

    /**
     * @var array
     *
     * Array Clave/valor que contendrá los idiomas disponibles
     * Se instancia en la función setForm y en principio se usará
     * para saber el nombre del idioma.
     */
    protected $languages = array();

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
     * Contiene los parámetros de la url: section, action e id
     */
    protected $routeParams = array();

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

    /**
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->addIdentifiers(array(
            'Application\Service\ServiceInterface',
            get_called_class()
        ));

        $this->eventManager = $eventManager;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }

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
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $application = $this->serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();
        $this->routeParams = $routeMatch->getParams();

        $this->setActionType($this->routeParams['action']);

        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setPrimaryKey($primaryKey)
    {
        $this->hiddenPrimaryKey = $primaryKey;
    }

    /**
     * Seteamos el tipo de action del formulario
     *
     * @param string $actionType
     */
    public function setActionType($actionType)
    {
        if ($this->form and !array_key_exists($actionType, $this->allowedActionType)) {
            throw new \Exception('Action Type ' . $actionType . ' not allowed');
        }

        $this->actionType = $actionType;

        return $this;
    }

    /**
     * Devuelve el tipo de action del formulario
     * Los resultados posibles son los definidos en la propiedad $allowedActionType
     *
     * @return string
     */
    public function getActionType()
    {
        return $this->actionType;
    }

    public function addFieldset($fieldsetName, AdministratorModel $model, $options = array())
    {
        $fieldset = new $fieldsetName($this->serviceLocator, $model);

        $fieldset->setObjectModel($model);

        $objectModel = $fieldset->getObjectModel();

        $isLocale = (isset($options['is_locale']) and $options['is_locale']);

        $fieldset->setOption('is_locale',$isLocale);

        if ($isLocale) {
            $fieldsetName .= "\\" . $objectModel->languageId;
            $fieldset->setName($fieldsetName);
            $fieldset->setOption('tab_name', $this->languages[$objectModel->languageId]);
        }

        if (!array_key_exists($fieldsetName, $this->fieldsets)) {

            $useAsBaseFieldset = isset($options['use_as_base_fieldset']);

            if ($useAsBaseFieldset) {
                $this->baseFieldset = $fieldset;
            }

            $this->initializers($fieldset);
            $this->fieldsets[$fieldsetName] = $fieldset;
        }
        return $this;
    }

    public function getBaseFieldset()
    {
        return $this->baseFieldset;
    }

    public function addLocaleFieldsets($localeClass, $options = array())
    {
        $baseTableGateway = $this->baseFieldset->getTableGateway();
        $objectModel = $this->baseFieldset->getObjectModel();

        $primaryId = isset($objectModel->id) ? $objectModel->id : 0;

        $localeTableGatewayName = preg_replace("/(Table)$/", "LocaleTable", get_class($baseTableGateway));

        $localeTableGateway = $this->serviceLocator->get($localeTableGatewayName);

        $localeModels = $localeTableGateway->findLocales($primaryId);

        foreach ($localeModels as $localModel) {
            $localModel->relatedTableId = $primaryId;
            $this->addFieldset($localeClass, $localModel, $options);
        }

        return $this;
    }

    public function setForm($form = null, AdministratorModel $model)
    {
        if (!$this->form) {

            $this->baseModel = $model;

            $this->languages = $this->serviceLocator->get('Administrator\Model\LanguageTable')->all()->toKeyValueArray('id','name');

            $form = new $form();

            //Como el nombre del formulario lo seteamos con el nombre de la clase,
            //Convertimos el separador de namespace en guiones bajos;
            $separatorToSeparator = new SeparatorToSeparator('\\','_');

            $this->form = $form->setName($separatorToSeparator->filter(get_class($form)));
            $this->form->setAttributes($this->defaultAttributes);

            $this
                ->addDefaultFields()
                ->setDefaultFormAction()
                ->initializers($this->form);

            $triggerInit = $this->routeParams['action'] == 'add'
                ? $this::EVENT_CREATE_INIT_FORM
                : $this::EVENT_UPDATE_INIT_FORM;

            $eventResult = $this->eventTrigger($triggerInit);
        }

        return $this;
    }

    public function initializers($instance)
    {
        if (method_exists($instance, 'initializers')) {
            $initializers = $instance->initializers($this->serviceLocator);

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
        if (!$this->form) {
            $this->setForm();
        }
        return $this->form;
    }

    public function getRouteParams($param = false)
    {
        if (is_string($param)) {
            return isset($this->routeParams[$param])
                ? $this->routeParams[$param]
                : false;
        }

        return $this->routeParams;
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

        $options['is_locale'] = $isLocale;

        if (!$isLocale) {
            $this->addFieldset($fieldset, $this->baseModel, $options);
        } else {
            $this->addLocaleFieldsets($fieldset, $options);
        }
    }

    public function addFields()
    {
        /**
         * Buscaremos en el objeto formulario y en los objectos Fieldset si existe el método addFields.
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

                if (in_array($column->getName(), array($this->hiddenPrimaryKey, $this->hiddenRelatedKey))) {
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
                $fieldset->{$thisMethod}($this->serviceLocator);
            }

            $this->form->add($fieldset);
        }

        if (method_exists($this->form, $thisMethod)) {
            $this->form->{$thisMethod}();
        }

        return $this;
    }

    protected function setFieldParams(ColumnObject $column)
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

    public function postCamelToUnderscore($post)
    {
        $toUnderscore = new CamelCaseToUnderscore();
        $underscoreVars = array();
        foreach ($post as $key => $value) {
            $underscoreVars[strtolower($toUnderscore->filter($key))] = $value;
        }
        return $underscoreVars;
    }

    protected function setFormDataType($columnName, $dataType)
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

    /**
     *  Seteamos el action por defecto en función de la url en la que nos encontramos
     *  Busca los segmentos "section", "action" e "id" y los
     *  rellena automáticamente.
     */
    private function setDefaultFormAction()
    {
        $viewHelper  = $this->serviceLocator->get('ViewHelperManager');

        $params = array(
            'module' => $this->routeParams['module'],
            'action'  => $this->routeParams['action'],
        );

        if (isset($this->routeParams['id'])) {
            $params['id'] = $this->routeParams['id'];
        }

        $url = $viewHelper->get('url');

        $this->form->setAttribute('action', $url('administrator',$params));

        return $this;
    }

    /**
     *  Añadimos los elementos de formulario que en principio deben aparecer por defecto
     *  Dicha función se ejecute desde el servicio GestorFormService
     */
    private function addDefaultFields()
    {
        $actionType = $this->actionType == self::ACTION_ADD ? 'Add' : 'Edit';

        $this->form->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => $actionType,
                'id' => 'submitbutton',
                'class' => 'btn btn-primary',
            ),
            'options' => array(
                'label' => $actionType,
            )
        ),array(
            'priority' => '-9999'
        ));

        return $this;
    }

    /**
     * @param array $params - Redefine los parámetros que se setean automáticamente
     */
    public function setUrlAction($params = array())
    {
        $viewHelper  = $this->serviceLocator->get('ViewHelperManager');

        $url = $viewHelper->get('url');

        $this->setAttribute('action', $url('administrator', $params));
    }

    public function resolveForm($data)
    {
        $this->form->bind($data);

        $isValid = true;

        if ($this->form->isValid()) {
            $this->eventTrigger($this::EVENT_CREATE_VALID_FORM_SUCCESS);
        } else {
            $isValid = false;
            $this->eventTrigger($this::EVENT_CREATE_VALID_FORM_FAILED);
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