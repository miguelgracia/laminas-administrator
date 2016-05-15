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
                'gestorControladorId' => 'Select'
            ),
            'fieldValueOptions' => array(
                'gestorControladorId' => function ($serviceLocator) {
                    $gestorControladorTable = $serviceLocator->get('AmController\Model\ControllerTable');

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