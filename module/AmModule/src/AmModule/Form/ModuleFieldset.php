<?php

namespace AmModule\Form;

use Administrator\Form\AdministratorFieldset;
use AmModule\Model\ModuleTable;

class ModuleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = ModuleTable::class;

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(

            ),
            'fieldValueOptions' => array(

            )
        );
    }

    public function addFields()
    {
        $this->get('zendName')->setAttribute('readonly','readonly');
    }
}