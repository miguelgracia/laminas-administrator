<?php

namespace AmMenu\Form\Element;

use AmModule\Service\ModuleService;
use Zend\Form\Element\Select;

class Action extends Select
{
    public function __construct(ModuleService $moduleService, $name = null, $options = [])
    {
        parent::__construct($name, $options);

        $modules = $moduleService->getControllerActionsModules();

        $modulesArray = [];

        foreach ($modules as $action => $module) {
            $explode = explode('.', $action);
            if (!isset($modulesArray[$explode[0]])) {
                $modulesArray[$explode[0]] = [
                    'label' => $explode[0],
                    'options' => []
                ];
            }
            $modulesArray[$explode[0]]['options'][$explode[1]] = $explode[1];
        }

        $this->setValueOptions(
            ['' => 'Selecciona una acción'] + $modulesArray
        );
    }
}
