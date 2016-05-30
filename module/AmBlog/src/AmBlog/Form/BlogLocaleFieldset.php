<?php

namespace AmBlog\Form;

use Administrator\Form\AdministratorFieldset;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlogLocaleFieldset extends AdministratorFieldset
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldModifiers' => array(
                'blogEntriesId' => 'Hidden',
            ),
            'fieldValueOptions' => array(

            )
        );
    }

    public function getHiddenFields()
    {
        return array(
            'locale',

            'languageId'
        );
    }
}

