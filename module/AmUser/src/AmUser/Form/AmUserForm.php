<?php

namespace AmUser\Form;

use Administrator\Form\AdministratorForm;
use Zend\ServiceManager\ServiceLocatorInterface;

class AmUserForm extends AdministratorForm {

    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldsets' => array(
                UserFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
            )
        );
    }
}