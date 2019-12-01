<?php

namespace Administrator\Service;

use Administrator\Form\AdministratorForm;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
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
     * @var Fieldset
     */
    protected $baseFieldset = null;

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
     * @param $formElementManager
     */
    public function __construct($formElementManager)
    {
        $this->formElementManager = $formElementManager;
    }

    public function eventTrigger($eventName,  $args = array())
    {
        $args = array('formService' => $this) + $args;
        // En result almacenamos el primer resultado de todos los listener que están escuchando
        // el evento $eventName. En principio el único que va a mandar resultado va a ser
        // CrudListener. Si hubiese algún otro Listener definido, sería para aplicar lógica
        // que no interfiera con el guardado en base de datos.


        return $this->getEventManager()->trigger($eventName,null,$args)/*->first()*/;
    }

    public function getBaseFieldset()
    {
        return $this->baseFieldset;
    }

    public function setBaseFieldset($baseFieldset)
    {
        $this->baseFieldset = $baseFieldset;
    }

    public function prepareForm($form = null, $action)
    {
        $this->form = $this->formElementManager->build($form);

        $triggerInit = $action == AdministratorForm::ACTION_ADD
            ? AdministratorFormService::EVENT_CREATE_INIT_FORM
            : AdministratorFormService::EVENT_UPDATE_INIT_FORM;

        $eventResult = $this->eventTrigger($triggerInit);

        return $this->form;
    }

    public function resolveForm($data)
    {
        $this->form->bind($data);

        if ($this->form->isValid()) {
            $this->eventTrigger(self::EVENT_CREATE_VALID_FORM_SUCCESS);
            return true;
        }

        $this->eventTrigger(self::EVENT_CREATE_VALID_FORM_FAILED);
        return false;
    }

    public function save()
    {
        $result = array();

        $baseFieldset = $this->baseFieldset;

        $primaryId = $baseFieldset->getTableGateway()->save($baseFieldset->getObjectModel());

        $result[] = $primaryId;

        $this->form->remove(get_class($baseFieldset));

        foreach ($this->form->getFieldsets() as $fieldset) {

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