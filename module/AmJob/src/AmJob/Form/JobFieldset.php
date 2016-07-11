<?php

namespace AmJob\Form;

use Administrator\Form\AdministratorFieldset;

class JobFieldset extends AdministratorFieldset
{
    public function initializers()
    {
        $serviceLocator = $this->serviceLocator->getServiceLocator();
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
}