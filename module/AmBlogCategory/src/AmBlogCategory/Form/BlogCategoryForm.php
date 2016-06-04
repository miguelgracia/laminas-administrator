<?php

namespace AmBlogCategory\Form;

use Administrator\Form\AdministratorForm;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlogCategoryForm extends AdministratorForm
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldsets' => array(
                BlogCategoryFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
                BlogCategoryLocaleFieldset::class => array()
            )
        );
    }
}
