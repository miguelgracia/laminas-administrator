<?php

namespace AmBlog\Form;

use Administrator\Form\AdministratorFieldset;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlogFieldset extends AdministratorFieldset
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldModifiers' => array(
                'blogCategoriesId' => 'Select'
            ),
            'fieldValueOptions' => array(
                'blogCategoriesId' => function () use($serviceLocator) {
                    return $serviceLocator->get('AmBlogCategory\Model\BlogCategoryTable')->all()->toKeyValueArray('id','key');
                },
                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
            )
        );
    }
}

