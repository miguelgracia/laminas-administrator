<?php

namespace AmProfile\Form;

use Administrator\Form\AdministratorForm;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProfileForm extends AdministratorForm {

    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        $form = $this;

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