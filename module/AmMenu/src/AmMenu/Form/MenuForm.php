<?php

namespace AmMenu\Form;

use Administrator\Form\AdministratorForm;
use Zend\ServiceManager\ServiceLocatorInterface;

class MenuForm extends AdministratorForm {

    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldsets' => array(
                MenuFieldset::class => array(
                    'use_as_base_fieldset' => true
                )
            )
        );
    }
}