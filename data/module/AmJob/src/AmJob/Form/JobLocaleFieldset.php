<?php

namespace AmJob\Form;

use Administrator\Form\AdministratorFieldset;
use AmJob\Model\JobLocaleTable;

class JobLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = JobLocaleTable::class;

    public function addFields()
    {
        $metaDescription = $this->get('metaDescription');
        $classes = $metaDescription->getAttribute('class');
        $metaDescription->setAttribute('class', $classes . ' no-editor');
    }
}