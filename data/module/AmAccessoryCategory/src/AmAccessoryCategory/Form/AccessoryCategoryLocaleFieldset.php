<?php

namespace AmAccessoryCategory\Form;

use Administrator\Form\AdministratorFieldset;
use AmAccessoryCategory\Model\AccessoryCategoryLocaleTable;

class AccessoryCategoryLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = AccessoryCategoryLocaleTable::class;
}
