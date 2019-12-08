<?php

namespace AmJobCategory\Form;

use Administrator\Form\AdministratorFieldset;
use AmJobCategory\Model\JobCategoryLocaleTable;

class JobCategoryLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = JobCategoryLocaleTable::class;
}
