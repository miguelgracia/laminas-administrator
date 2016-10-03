<?php

namespace AmMegabanner\Form;

use Administrator\Form\AdministratorFieldset;
use AmMegabanner\Model\MegabannerTable;

class MegabannerFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = MegabannerTable::class;

    public function initializers()
    {
        return array(
            'fieldValueOptions' => array(
                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
                'isVideo' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
            )
        );
    }

    public function addFields()
    {
        $elementUrl = $this->get('elementUrl');
        $class = $elementUrl->getAttribute('class');
        $class .= ' browsefile';
        $elementUrl->setAttribute('class',$class);
    }
}