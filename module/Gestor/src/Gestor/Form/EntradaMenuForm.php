<?php

namespace Gestor\Form;

use Zend\Form\Form;

class EntradaMenuForm extends Form {

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'padre'         => 'Hidden',
                'orden'         => 'Hidden',
                'idControlador' => 'Select'
            ),
            'fieldValueOptions' => array(
                'idControlador' => function ($serviceLocator) {
                    $gestorControladorTable = $serviceLocator->get('Gestor\Model\GestorControladorTable');

                    $gestorControlador = $gestorControladorTable->fetchAll();

                    // Es necesario que haya una primera opción vacía, para las entradas de menú sin enlace
                    $array = array('' => '[Ninguno]');
                    $arrayMerged = $array + $gestorControlador->toKeyValueArray('id','nombreUsable');
//var_dump($arrayMerged);

                    return $arrayMerged;
                }
            ),
        );
    }
}