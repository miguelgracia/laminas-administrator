<?php

namespace AmSection\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class SectionForm extends Form
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldsets' => array(
                SectionFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
                SectionLocaleFieldset::class => array(),
            )
        );
    }
}