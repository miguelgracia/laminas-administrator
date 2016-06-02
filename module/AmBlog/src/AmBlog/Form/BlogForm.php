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
                'baseFieldset' => BlogFieldset::class,
                'localeFieldset' => BlogLocaleFieldset::class
            )
        );
    }

}