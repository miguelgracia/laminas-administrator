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
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;

class AdministratorFormService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * @var FormElementManagerV3Polyfill
     */
    protected $formElementManager;

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
     * @var array
     *
     * Tipos de action de formulario permitidos y su correspondencia con eventos
     */
    protected $allowedActionType = array(
        AdministratorForm::ACTION_ADD        => self::EVENT_CREATE,
        AdministratorForm::ACTION_DEFAULT    => self::EVENT_READ,
        AdministratorForm::ACTION_EDIT       => self::EVENT_UPDATE,
        AdministratorForm::ACTION_DELETE     => self::EVENT_DELETE,
    );

    protected $actionType = AdministratorForm::ACTION_ADD;

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
     * Array clave / valor.
     * La clave corresponde a los id's que se van asignando a los elementos del formulario.
     * El valor es el número de veces que aparece ese id en el formulario.
     * Si un id se repite, le añadimos un sufijo númerico.
     */
    protected $elementsId = array();

    /**
     * @var array
     *
     * Contiene los parámetros de la url: section, action e id
     */
    protected $routeParams = array();

    /**
     * @param $formElementManager
     * @param $routeParams
     */
    public function __construct($formElementManager, $routeParams)
    {
        $this->formElementManager = $formElementManager;
        $this->routeParams = $routeParams;
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

    public function getBaseFieldset()
    {
        return $this->baseFieldset;
    }

    public function setBaseFieldset($baseFieldset)
    {
        $this->baseFieldset = $baseFieldset;
    }

    public function prepareForm($form = null, AdministratorModel $model)
    {
        $this->form = $this->formElementManager->build($form);

        $formInitializers = $this->form->initializers();

        $fieldsets = [];

        foreach ($formInitializers['fieldsets'] as $fieldsetName) {
            $isLocale = strpos($fieldsetName, "LocaleFieldset") !== false;

            if ($isLocale) {
                $localeFieldsets = $this->formElementManager->build($fieldsetName, [
                    'base_fieldset' => $this->baseFieldset,
                ]);

                $fieldsets += $localeFieldsets;

                continue;
            }

            $fieldset = $this->formElementManager->build($fieldsetName, [
                'model' => $model,
            ]);
            $fieldsets[$fieldset->getName()] = $fieldset;
        }

        $this->fieldsets = $fieldsets;

        $triggerInit = $this->getRouteParams('action') == 'add'
            ? AdministratorFormService::EVENT_CREATE_INIT_FORM
            : AdministratorFormService::EVENT_UPDATE_INIT_FORM;

        $eventResult = $this->eventTrigger($triggerInit);

        $this->addElements();

        return $this->form;
    }

    public function addElements()
    {
        /**
         * Buscaremos en el objeto formulario y en los objetos Fieldset si existe el método addElements.
         * En caso afirmativo, lo ejecutamos para poder añadir campos adicionales
         * que se salga de la lógica predeterminada o, por ejemplo, redefinir
         * el atributo de algún campo concreto.
         */
        $thisMethod = substr(strrchr(__METHOD__, '::'), 1);

        foreach ($this->fieldsets as &$fieldset) {
            $this->form->add($fieldset);
        }

        if (method_exists($this->form, $thisMethod)) {
            $this->form->{$thisMethod}();
        }

        return $this;
    }

    public function resolveForm($data)
    {
        $form = $this->form;

        $form->bind($data);

        $isValid = true;

        if ($form->isValid()) {
            $this->eventTrigger(self::EVENT_CREATE_VALID_FORM_SUCCESS);
        } else {
            $isValid = false;
            $this->eventTrigger(self::EVENT_CREATE_VALID_FORM_FAILED);
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