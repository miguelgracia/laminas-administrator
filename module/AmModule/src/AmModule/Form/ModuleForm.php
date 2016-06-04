<?php

namespace AmModule\Form;

use Administrator\Form\AdministratorForm;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleForm extends AdministratorForm {

    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldsets' => array(
                ModuleFieldset::class => array(
                    'use_as_base_fieldset' => true
                )
            )
        );
    }
}