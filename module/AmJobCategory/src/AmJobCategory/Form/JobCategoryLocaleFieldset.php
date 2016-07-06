<?php

namespace AmJobCategory\Form;

use Administrator\Form\AdministratorFieldset;
use Zend\ServiceManager\ServiceLocatorInterface;

class JobCategoryLocaleFieldset extends AdministratorFieldset
{

    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldModifiers' => array(
                'jobCategoriesId' => 'Hidden',
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
            'languageId'
        );
    }
}