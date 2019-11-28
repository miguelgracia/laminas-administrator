<?php

namespace AmMenu\Form;

use Administrator\Form\AdministratorFieldset;
use AmMenu\Model\MenuTable;

class MenuFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = MenuTable::class;
}