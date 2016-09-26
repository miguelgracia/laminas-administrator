<?php

namespace AmJobCategory\Form;

use Administrator\Form\AdministratorFieldset;
use AmJobCategory\Model\JobCategoryLocaleTable;

class JobCategoryLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = JobCategoryLocaleTable::class;

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'jobCategoriesId' => 'Hidden',
                'metaDescription'   => 'Textarea',
                'content'           => 'Textarea',
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
}