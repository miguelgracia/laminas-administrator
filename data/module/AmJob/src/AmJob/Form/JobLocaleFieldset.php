<?php

namespace AmJob\Form;

use Administrator\Form\AdministratorFieldset;
use AmJob\Model\JobLocaleTable;

class JobLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = JobLocaleTable::class;
}
