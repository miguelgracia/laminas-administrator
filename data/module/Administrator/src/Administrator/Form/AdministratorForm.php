<?php


namespace Administrator\Form;


use Administrator\Traits\ServiceLocatorAwareTrait;
use Zend\Filter\Word\SeparatorToSeparator;
use Zend\Form\Form;

class AdministratorForm extends Form
{
    use ServiceLocatorAwareTrait;

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
        $serviceLocator = $this->serviceLocator;

        $application = $serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();
        $this->routeParams = $routeMatch->getParams();

        $this->setAttribute('class', 'form-horizontal');

        /**
         * Como el nombre del formulario lo seteamos con el nombre de la clase,
         * convertimos el separador de namespace en guiones bajos;
         */
        $separatorToSeparator = new SeparatorToSeparator('\\','_');
        $this->setName($separatorToSeparator->filter(get_class($this)));

        $this->setDefaultAction();

        $this->setActionType($this->routeParams['action']);
        $this->addDefaultFields();
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
     *  Seteamos el action por defecto en función de la url en la que nos encontramos
     *  Busca los segmentos "section", "action" e "id" y los
     *  rellena automáticamente.
     */
    private function setDefaultAction()
    {
        $viewHelper  = $this->serviceLocator->get('ViewHelperManager');

        $application = $this->serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();
        $routeParams = $routeMatch->getParams();

        $params = array(
            'module' => $routeParams['module'],
            'action'  => $routeParams['action'],
        );

        if (isset($routeParams['id'])) {
            $params['id'] = $routeParams['id'];
        }

        $url = $viewHelper->get('url');

        $this->setAttribute('action', $url('administrator',$params));

        return $this;
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