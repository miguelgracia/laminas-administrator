<?php

namespace AmMegabanner\Form;

use Administrator\Form\AdministratorFieldset;
use AmMegabanner\Model\MegabannerLocaleTable;

class MegabannerLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = MegabannerLocaleTable::class;

    public function addFields()
    {
        $imageUrl = $this->get('imageUrl');
        $class = $imageUrl->getAttribute('class');
        $class .= ' browsefile';
        $imageUrl->setAttribute('class',$class);
    }

    public function getHiddenFields()
    {
        return array(
            'locale',
            'languageId'
        );
    }
}