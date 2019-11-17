<?php

namespace AmSection\Form;

use Administrator\Form\AdministratorFieldset;
use AmSection\Model\SectionLocaleTable;

class SectionLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = SectionLocaleTable::class;

    public function addFields()
    {
        $metaDescription = $this->get('metaDescription');
        $classes = $metaDescription->getAttribute('class');
        $metaDescription->setAttribute('class', $classes . ' no-editor');
    }
}