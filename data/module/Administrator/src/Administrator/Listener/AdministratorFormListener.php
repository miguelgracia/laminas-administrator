<?php

namespace Administrator\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;

class AdministratorFormListener implements ListenerAggregateInterface
{
    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService','create.init.form',array($this,'onCreateInitForm'),100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService','create.form.valid.success',array($this,'onCreateFormValidSuccess'),100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService','create.form.valid.failed',array($this,'onCreateFormValidFailed'),100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService','create.form.save',array($this,'onCreateFormSave'),100);

        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService','update.init.form',array($this,'onUpdateInitForm'),100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService','update.form.valid.success',array($this,'onUpdateFormValidSuccess'),100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService','update.form.valid.failed',array($this,'onUpdateFormValidFailed'),100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService','update.form.save',array($this,'onUpdateFormSave'),100);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
}