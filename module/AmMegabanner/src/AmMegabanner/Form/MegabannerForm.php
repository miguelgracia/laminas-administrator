<?php

namespace AmMegabanner\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class MegabannerForm extends Form
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldsets' => array(
                MegabannerFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
                MegabannerLocaleFieldset::class => array(),
            )
        );
    }
}

