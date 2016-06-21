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
                'content'           => 'Textarea',
                'metaDescription'   => 'Textarea'
            ),
            'fieldValueOptions' => array(

            )
        );
    }

    public function addFields()
    {
        $metaDescription = $this->get('metaDescription');
        $classes = $metaDescription->getAttribute('class');
        $metaDescription->setAttribute('class', $classes . ' no-editor');
    }

    public function getHiddenFields()
    {
        return array(
            'locale',
            'languageId'
        );
    }
}

