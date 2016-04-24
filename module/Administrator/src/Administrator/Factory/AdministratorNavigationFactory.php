<?php

namespace Administrator\Factory;


use Administrator\Navigation\AdministratorNavigation;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdministratorNavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $navigation = new AdministratorNavigation();
        return $navigation->createService($serviceLocator);
    }
}