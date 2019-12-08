<?php

namespace AmJobCategory\Form;

use Administrator\Form\AdministratorFieldset;
use AmJobCategory\Model\JobCategoryTable;

class JobCategoryFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = JobCategoryTable::class;
}
