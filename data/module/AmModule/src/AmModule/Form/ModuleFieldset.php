<?php

namespace AmModule\Form;

use Administrator\Form\AdministratorFieldset;
use AmModule\Model\ModuleTable;

class ModuleFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = ModuleTable::class;

    public function addFields()
    {
        $this->get('zendName')->setAttribute('readonly','readonly');
    }
}