<?php

namespace AmJob\Form;

use Administrator\Form\AdministratorFieldset;
use Zend\ServiceManager\ServiceLocatorInterface;

class JobFieldset extends AdministratorFieldset
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldModifiers' => array(
                'jobCategoriesId' => 'Select'
            ),
            'fieldValueOptions' => array(
                'jobCategoriesId' => function () use($serviceLocator) {
                    return $serviceLocator->get('AmJobCategory\Model\JobCategoryTable')->all()->toKeyValueArray('id','key');
                },
                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
            )
        );
    }

    public function addFields()
    {
        $date = $this->get('date');
        $date->setAttribute('placeholder','dd-mm-yyyy');
    }
}

