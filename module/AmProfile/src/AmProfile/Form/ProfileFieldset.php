<?php

namespace AmProfile\Form;

use Administrator\Form\AdministratorFieldset;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProfileFieldset extends AdministratorFieldset
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldModifiers' => array(
                'descripcion'   => 'textarea',
                'permisos' => 'MultiCheckbox',
                'esAdmin' => 'Select'
            ),
            'fieldValueOptions' => array(
                'esAdmin' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
                'permisos' => function () use($serviceLocator) {

                    return $serviceLocator->get('AmModule\Service\ModuleService')->getControllerActionsModules();

                }
            )
        );
    }

    public function addFields()
    {
        $perm = $this->get('permisos');

        $perm->setAttribute('class', '');

        $perm->setLabelAttributes(array(
            'class' => 'col-sm-4'
        ));

        return $this;
    }
}