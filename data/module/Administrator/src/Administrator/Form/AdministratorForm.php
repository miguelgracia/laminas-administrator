<?php


namespace Administrator\Form;

use Zend\Form\Form;

class AdministratorForm extends Form
{
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

    protected $actionType = self::ACTION_ADD;

    /**
     * @var array
     *
     * Contiene los parámetros de la url: section, action e id
     */
    protected $routeParams = array();

    /**
     * @var Fieldset Primario.
     */
    protected $primaryFieldset = null;


    protected function setPrimaryFieldset(AdministratorFieldset $fieldset)
    {
        $this->primaryFieldset = $fieldset;
    }

    protected function getPrimaryFieldset()
    {
        return $this->primaryFieldset;
    }


    public function init()
    {
        $this->addDefaultFields();
    }

    public function setRouteParams($params)
    {
        $this->routeParams = $params;
        return $this;
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
     * Seteamos el tipo de action del formulario
     *
     * @param string $actionType
     */
    public function setActionType($actionType)
    {
        if (!array_key_exists($actionType, $this->allowedActionType)) {
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

    /**
     *  Añadimos los elementos de formulario que en principio deben aparecer por defecto
     *  Dicha función se ejecute desde el servicio GestorFormService
     */
    private function addDefaultFields()
    {
        $actionType = $this->actionType == self::ACTION_ADD ? 'Add' : 'Edit';

        $this->add(array(
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
}