<?php

namespace AmBlogCategory\Form;

use Administrator\Form\AdministratorFieldset;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlogCategoryLocaleFieldset extends AdministratorFieldset
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldModifiers' => array(
                'blogCategoriesId' => 'Hidden',
            ),
            'fieldValueOptions' => array(

            )
        );
    }

    public function getHiddenFields()
    {
        return array(
            'languageId'
        );
    }
}

