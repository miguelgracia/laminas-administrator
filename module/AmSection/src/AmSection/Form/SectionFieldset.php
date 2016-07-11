<?php

namespace AmSection\Form;

use Administrator\Form\AdministratorFieldset;
use AmSection\Model\SectionTable;

class SectionFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = SectionTable::class;

    public function initializers()
    {
        return array(
            'fieldValueOptions' => array(
                'position' => array(
                    'header' => 'cabecera',
                    'footer' => 'Pie de página'
                ),
                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                )
            )
        );
    }

    public function addFields()
    {

    }
}