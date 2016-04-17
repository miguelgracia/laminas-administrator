<?php

namespace Gestor\Factory;


use Gestor\Navigation\GestorNavigation;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GestorNavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $navigation = new GestorNavigation();
        return $navigation->createService($serviceLocator);
    }
}