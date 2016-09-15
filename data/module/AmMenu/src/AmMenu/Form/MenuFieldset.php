<?php

namespace AmMenu\Form;

use Administrator\Form\AdministratorFieldset;
use AmMenu\Model\MenuTable;

class MenuFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = MenuTable::class;

    public function initializers()
    {
        $serviceLocator = $this->serviceLocator->getServiceLocator();

        $moduleTable = $serviceLocator->get('AmModule\Model\ModuleTable');

        $moduleController = $moduleTable->all()->toKeyValueArray('id','zendName');

        return array(
            'fieldModifiers' => array(
                'parent'         => 'Hidden',
                'order'         => 'Hidden',
                'adminModuleId' => 'Select',
                'action'        => 'Select'
            ),
            'fieldValueOptions' => array(
                'adminModuleId' => function () use($moduleController) {

                    return array('' => '[Ninguno]') + $moduleController;
                },
                'action' => function () use($serviceLocator, $moduleController) {

                    $modules = $serviceLocator->get('AmModule\Service\ModuleService')->getControllerActionsModules();

                    $modulesArray = array();

                    foreach ($modules as $action => $module) {
                        $explode = explode('.',$action);
                        if (!isset($modulesArray[$explode[0]])) {
                            $modulesArray[$explode[0]] = array(
                                'label' => $explode[0],
                                'options' => array()
                            );
                        }
                        $modulesArray[$explode[0]]['options'][$explode[1]] = $explode[1];
                    }

                    return array('' => 'Selecciona una acción') + $modulesArray;
                }
            ),
        );
    }

    public function addFields()
    {
        $serviceLocator = $this->serviceLocator->getServiceLocator();

        $formService = $serviceLocator->get('Administrator\Service\AdministratorFormService');

        if ($formService->getForm()->getActionType() == 'add') {
            $padre = (int) $formService->getRouteParams('id');

            $fieldset = $formService->getBaseFieldset();

            $fieldset->get('parent')->setValue($padre);
        }
    }
}