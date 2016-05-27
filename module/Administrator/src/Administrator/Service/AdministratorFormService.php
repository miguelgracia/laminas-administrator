<?php

/*
 * Esto es un servicio gen�rico de apoyo a formularios.
 */

namespace Administrator\Service;

use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Metadata\Metadata;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\Filter\Word\SeparatorToCamelCase;
use Zend\Filter\Word\SeparatorToSeparator;
use Zend\Filter\Word\UnderscoreToCamelCase;
use Zend\Form\Form;

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
    const EVENT_CREATE      = 'create';
    const EVENT_READ        = 'read';
    const EVENT_UPDATE      = 'update';
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
     * @var \Zend\Db\Metadata\Metadata
     *
     * Nos da acceso a m�todos para tratamiento de las tablas de base de datos.
     * Listado de columnas, tipo de dato de las columnas, etc
     */
    protected $metadata;

    /**
     * @var
     *
     * Objeto TableGateway de donde extraremos el nombre de la tabla de
     * base de datos que alimentar� el formulario
     */
    protected $tableGateway;

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
     */
    protected $fieldsets = array();

    /**
     * @var array
     *
     * Contiene los par�metros de la url: section, action e id
     */
    protected $routeParams = array();

    /**
     * @var array
     *
     * Array asociativo que contendr� un conjunto de registros clave/valor para rellenar
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

        $result = $this->getEventManager()->trigger($eventName,null,$args)->first();

        return $result;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return $this
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        $this->metadata = new Metadata($this->serviceLocator->get('Zend\Db\Adapter\Adapter'));

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

    /**
     * Seteamos el objeto tableGateway que va a alimentar el formulario.
     * Se llama desde la función setControllerVars de los controladores.
     *
     * @param $table
     * @return $this
     *
     * @param AdapterAwareInterface $table
     * @return $this
     */
    public function setTable(AdapterAwareInterface $tablegateway)
    {
        $this->tableGateway = $tablegateway;
        return $this;
    }

    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    public function getTableEntityModel()
    {
        return $this->tableGateway->getEntityModel();
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

    public function addFieldset($fieldset)
    {
        $className = get_class($fieldset);

        if (!array_key_exists($className, $this->fieldsets)) {
            $this->initializers($fieldset);
            $this->fieldsets[$className] = $fieldset;
        }

        return $this;
    }

    public function setForm(Form $form = null)
    {
        if (!$this->form) {

            if (is_null($form) or !$form instanceof Form) {
                $form = new Form();
            }

            //Como el nombre del formulario lo seteamos con el nombre de la clase,
            //Convertimos el separador de namespace en guiones bajos;
            $separatorToSeparator = new SeparatorToSeparator('\\','_');

            $this->form = $form->setName($separatorToSeparator->filter(get_class($form)));
            $this->form->setAttributes($this->defaultAttributes);

            $this
                ->addDefaultFields()
                ->setDefaultFormAction()
                ->initializers($this->form);
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

    public function addFields($sourceTable = null)
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

            foreach ($columns as $column) {

                $toCamel = new SeparatorToCamelCase('_');
                $columnName = lcfirst($toCamel->filter($column->getName()));

                $flags = array(
                    'priority' => -($column->getOrdinalPosition() * 100),
                );

                $fieldParams = array(
                    'name' => $columnName,
                    'label' => $columnName,
                    'options' => array(
                        'label' => $columnName,
                        'label_attributes' => array(
                            'class' => 'col-sm-2 control-label'
                        ),
                        'priority' => -($column->getOrdinalPosition() * 100),
                    ),
                    'attributes' => array(
                        'id' => $columnName,
                        'class' => 'form-control',
                    )
                );

                if ($columnName == $this->hiddenPrimaryKey) {
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

    public function addFieldsOld($sourceTable = null)
    {
        if (!$sourceTable) {
            $sourceTable = $this->tableGateway->getTable();
        }

        $columns = $this->metadata->getColumns($sourceTable);

        foreach ($columns as $column) {
            $toCamel = new SeparatorToCamelCase('_');
            $columnName = lcfirst($toCamel->filter($column->getName()));

            $flags = array(
                'priority' => -($column->getOrdinalPosition() * 100),
            );

            $fieldParams = array(
                'name' => $columnName,
                'label' => $columnName,
                'options' => array(
                    'label' => $columnName,
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label'
                    ),
                    'priority' => -($column->getOrdinalPosition() * 100),
                ),
                'attributes' => array(
                    'id' => $columnName,
                    'class' => 'form-control',
                )
            );

            if ($columnName == $this->hiddenPrimaryKey) {
                $fieldParams['type'] = 'Hidden';
                $this->form->add($fieldParams, $flags);
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

            $this->form->add($fieldParams,$flags);
        }

        /**
         * Buscamos en el objeto formulario si existe el método addFields.
         * En caso afirmativo, lo ejecutamos para poder añadir campos adicionales
         * que se salga de la lógica predeterminada o, por ejemplo, redefinir
         * el atributo de algún campo concreto. (Vease Gestor\Form\GestorUsuariosForm)
         */
        $thisMethod = substr(strrchr(__METHOD__, '::'), 1);

        if (method_exists($this->form, $thisMethod)) {
            $this->form->{$thisMethod}();
        }

        return $this;
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
}