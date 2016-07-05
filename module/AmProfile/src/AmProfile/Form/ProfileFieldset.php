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

        $perm->setOption('partial_view','am-profile/am-profile-module/form-partial/permission');

        $perm->setOption('label','permisos');
        $perm->setAttribute('class', '');
        $perm->setUseHiddenElement(true);

        $perm->setLabelAttributes(array(
            'class' => 'col-sm-3'
        ));

        //Añadimos la clase no-editor para que no cargue el plugin ckeditor en este campo
        $description = $this->get('descripcion');
        $classes = $description->getAttribute('class');
        $description->setAttribute('class', $classes . ' no-editor');

        return $this;
    }
}