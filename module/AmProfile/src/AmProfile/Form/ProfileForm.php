<?php

namespace AmProfile\Form;

use Administrator\Form\AdministratorForm;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProfileForm extends AdministratorForm {

    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldsets' => array(
                ProfileFieldset::class => array(
                    'use_as_base_fieldset' => true
                )
            )
        );
    }
}