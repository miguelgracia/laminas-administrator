<?php

namespace Administrator\Service;

use Laminas\ServiceManager\AbstractPluginManager;

class DatatablePluginManager extends AbstractPluginManager
{
    protected $instanceOf = DatatableConfigInterface::class;
}
