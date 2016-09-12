<?php

namespace AmProfile\Form;

use Administrator\Form\AdministratorFieldset;
use AmProfile\Model\ProfileTable;

class ProfileFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = ProfileTable::class;

    public function initializers()
    {
        $serviceLocator = $this->serviceLocator->getServiceLocator();

        return array(
            'fieldModifiers' => array(
                'description'   => 'textarea',
                'permissions' => 'MultiCheckbox',
                'isAdmin' => 'Select'
            ),
            'fieldValueOptions' => array(
                'isAdmin' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
                'permissions' => function () use($serviceLocator) {

                    return $serviceLocator->get('AmModule\Service\ModuleService')->getControllerActionsModules();

                }
            )
        );
    }

    public function addFields()
    {
        $perm = $this->get('permissions');

        $perm->setOption('partial_view','am-profile/am-profile-module/form-partial/permission');

        $perm->setOption('label','permissions');
        $perm->setAttribute('class', '');
        $perm->setUseHiddenElement(true);

        $perm->setLabelAttributes(array(
            'class' => 'col-sm-3'
        ));

        //Añadimos la clase no-editor para que no cargue el plugin ckeditor en este campo
        $description = $this->get('description');
        $classes = $description->getAttribute('class');
        $description->setAttribute('class', $classes . ' no-editor');

        return $this;
    }
}