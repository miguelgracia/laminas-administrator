<?php

namespace AmBlog\Form;

use Administrator\Form\AdministratorForm;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlogForm extends AdministratorForm
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldsets' => array(
                BlogFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
                BlogLocaleFieldset::class => array(),
            )
        );
    }
}