<?php

namespace AmMegabanner\Form;

use Administrator\Form\AdministratorFieldset;
use AmMegabanner\Model\MegabannerLocaleTable;

class MegabannerLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = MegabannerLocaleTable::class;

    public function addFields()
    {
        $elementUrl = $this->get('elementUrl');
        $class = $elementUrl->getAttribute('class');
        $class .= ' browsefile';
        $elementUrl->setAttribute('class',$class);
    }

    public function getHiddenFields()
    {
        return array(
            'locale',
            'languageId'
        );
    }
}