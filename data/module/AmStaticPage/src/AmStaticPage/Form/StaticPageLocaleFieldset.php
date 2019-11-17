<?php

namespace AmStaticPage\Form;

use Administrator\Form\AdministratorFieldset;
use AmStaticPage\Model\StaticPageLocaleTable;
use Zend\ServiceManager\ServiceLocatorInterface;

class StaticPageLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = StaticPageLocaleTable::class;
}