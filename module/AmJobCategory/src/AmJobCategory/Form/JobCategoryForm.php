<?php

namespace AmJobCategory\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class JobCategoryForm extends Form
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldsets' => array(
                JobCategoryFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
                JobCategoryLocaleFieldset::class => array()
            )
        );
    }
}