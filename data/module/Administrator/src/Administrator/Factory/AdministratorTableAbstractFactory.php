<?php

namespace Administrator\Factory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdministratorTableAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
        return (substr($requestedName, -5) === 'Table');
    }

    public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
        if (!$locator->has($requestedName)) {
            $locator->setInvokableClass($requestedName, $requestedName);
        }

        return $locator->get($requestedName);
    }
}