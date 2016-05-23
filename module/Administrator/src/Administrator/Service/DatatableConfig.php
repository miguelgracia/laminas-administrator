<?php

namespace Administrator\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

class DatatableConfig
{
    protected $serviceLocator;
    protected $controllerPluginManager;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $this->controllerPluginManager = $this->serviceLocator->get('ControllerPluginManager');
    }
}