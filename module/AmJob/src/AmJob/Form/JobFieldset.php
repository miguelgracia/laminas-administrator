<?php

namespace AmJob\Form;

use Administrator\Form\AdministratorFieldset;
use AmJob\Model\JobTable;

class JobFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = JobTable::class;

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

    public function addFields()
    {
        $imageUrl = $this->get('imageUrl');
        $class = $imageUrl->getAttribute('class');
        $class .= ' browsefile';
        $imageUrl->setAttribute('class',$class);

        $imageUrl->setOption('partial_view','am-job/am-job-module/form-partial/image-url');
        $imageUrl->setOption('allow_add_multiple_files', true);
    }
}