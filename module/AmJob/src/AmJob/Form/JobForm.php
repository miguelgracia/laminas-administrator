<?php

namespace AmJob\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class JobForm extends Form
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldsets' => array(
                JobFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
                JobLocaleFieldset::class => array(),
            )
        );
    }

}

