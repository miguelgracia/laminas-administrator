<?php

namespace Administrator\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;

class AdministratorFormListener implements ListenerAggregateInterface
{
    protected $listeners = [];

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents = $events->getSharedManager();

        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService', 'create.init.form', [$this, 'onCreateInitForm'], 100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService', 'create.form.valid.success', [$this, 'onCreateFormValidSuccess'], 100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService', 'create.form.valid.failed', [$this, 'onCreateFormValidFailed'], 100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService', 'create.form.save', [$this, 'onCreateFormSave'], 100);

        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService', 'update.init.form', [$this, 'onUpdateInitForm'], 100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService', 'update.form.valid.success', [$this, 'onUpdateFormValidSuccess'], 100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService', 'update.form.valid.failed', [$this, 'onUpdateFormValidFailed'], 100);
        $this->listeners[] = $sharedEvents->attach('Administrator\Service\AdministratorFormService', 'update.form.save', [$this, 'onUpdateFormSave'], 100);
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
