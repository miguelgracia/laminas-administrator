<?php

namespace AmSection\Form;

use Administrator\Form\AdministratorFieldset;
use AmSection\Model\SectionLocaleTable;

class SectionLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = SectionLocaleTable::class;
}