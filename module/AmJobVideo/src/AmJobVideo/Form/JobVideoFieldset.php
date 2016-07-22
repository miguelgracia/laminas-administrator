<?php

namespace AmJobVideo\Form;

use Administrator\Form\AdministratorFieldset;
use AmJobVideo\Model\JobVideoTable;

class JobVideoFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = JobVideoTable::class;
}