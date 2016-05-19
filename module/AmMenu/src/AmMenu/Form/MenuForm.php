<?php

namespace AmMenu\Form;

use Zend\Form\Form;

class MenuForm extends Form {

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'padre'         => 'Hidden',
                'orden'         => 'Hidden',
                'gestorModuleId' => 'Select'
            ),
            'fieldValueOptions' => array(
                'gestorModuleId' => function ($serviceLocator) {

                    $controllerManager = $serviceLocator->get('ControllerManager');
                    $moduleManager = $serviceLocator->get('ModuleManager');

                    $application = $serviceLocator->get('Application');

                    $modulo = $controllerManager->get('AmHome\Controller\AmHomeModuleController');

                    $gestorControladorTable = $serviceLocator->get('AmModule\Model\ModuleTable');

                    $gestorControlador = $gestorControladorTable->fetchAll();

                    // Es necesario que haya una primera opci�n vac�a, para las entradas de men� sin enlace
                    $array = array('' => '[Ninguno]');
                    $arrayMerged = $array + $gestorControlador->toKeyValueArray('id','nombreUsable');

                    return $arrayMerged;
                }
            ),
        );
    }
}