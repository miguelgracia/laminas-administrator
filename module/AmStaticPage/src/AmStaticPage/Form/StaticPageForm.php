<?php

namespace AmStaticPage\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class StaticPageForm extends Form
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldsets' => array(
                StaticPageFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
                StaticPageLocaleFieldset::class => array(),
            )
        );
    }
}