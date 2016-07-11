<?php

namespace AmMegabanner\Form;

use Administrator\Form\AdministratorFieldset;
use AmMegabanner\Model\MegabannerTable;

class MegabannerFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = MegabannerTable::class;

    public function initializers()
    {
        return array(
            'fieldValueOptions' => array(
                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
            )
        );
    }
}