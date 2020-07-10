<?php

namespace AmAccessoryCategory\Form;

use Administrator\Form\AdministratorFieldset;
use AmAccessoryCategory\Model\AccessoryCategoryTable;

class AccessoryCategoryFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = AccessoryCategoryTable::class;
}
