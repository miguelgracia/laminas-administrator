<?php

namespace AmHomeModule\Form;

use Administrator\Form\AdministratorFieldset;
use AmHomeModule\Model\HomeModuleTable;

class HomeModuleFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = HomeModuleTable::class;

    public function initializers()
    {
        return array(
            'fieldValueOptions' => array(
                'targetLink' => array(
                    '_self' => '_self',
                    '_blank' => '_blank'
                ),
                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
            )
        );
    }

}

