<?php

namespace Administrator\Service;

use Zend\ServiceManager\AbstractPluginManager;

class DatatablePluginManager extends AbstractPluginManager
{
    protected $instanceOf = DatatableConfigInterface::class;
}