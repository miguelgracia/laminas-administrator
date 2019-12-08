<?php

namespace AmStaticPage\Form;

use Administrator\Form\AdministratorFieldset;
use AmStaticPage\Model\StaticPageLocaleTable;

class StaticPageLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = StaticPageLocaleTable::class;
}
