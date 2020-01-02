<?php

namespace AmAccessory\Form;

use Administrator\Form\AdministratorFieldset;
use AmAccessory\Model\AccessoryLocaleTable;

class AccessoryLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = AccessoryLocaleTable::class;
}
