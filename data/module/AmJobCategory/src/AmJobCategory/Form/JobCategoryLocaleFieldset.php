<?php

namespace AmJobCategory\Form;

use Administrator\Form\AdministratorFieldset;
use AmJobCategory\Model\JobCategoryLocaleTable;

class JobCategoryLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = JobCategoryLocaleTable::class;

    public function addFields()
    {
        $metaDescription = $this->get('metaDescription');
        $classes = $metaDescription->getAttribute('class');
        $metaDescription->setAttribute('class', $classes . ' no-editor');
    }
}