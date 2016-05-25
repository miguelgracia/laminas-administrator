<?php

namespace AmMenu\Form;


use Administrator\Form\AdministratorFieldset;
use Zend\ServiceManager\ServiceLocatorInterface;

class MenuFieldset extends AdministratorFieldset
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        $moduleTable = $serviceLocator->get('AmModule\Model\ModuleTable');

        $moduleController = $moduleTable->fetchAll()->toKeyValueArray('id','nombreZend');

        return array(
            'fieldModifiers' => array(
                'padre'         => 'Hidden',
                'orden'         => 'Hidden',
                'gestorModuleId' => 'Select',
                'accion'        => 'Select'
            ),
            'fieldValueOptions' => array(
                'gestorModuleId' => function () use($moduleController) {

                    return array('' => '[Ninguno]') + $moduleController;
                },
                'accion' => function () use($serviceLocator, $moduleController) {

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
}