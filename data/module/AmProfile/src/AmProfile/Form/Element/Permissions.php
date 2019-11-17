<?php

namespace AmProfile\Form\Element;

use AmModule\Service\ModuleService;
use Zend\Form\Element\MultiCheckbox;

class Permissions extends MultiCheckbox
{
    public function __construct(ModuleService $moduleService, $name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setValueOptions(
            $moduleService->getControllerActionsModules()
        );
    }

}