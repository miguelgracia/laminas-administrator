<?php

namespace Administrator\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class AdministratorModelAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return (substr($requestedName, -5) === 'Model');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!class_exists($requestedName)) {
            $requestedName = (new \ReflectionClass($requestedName))->getNamespaceName() . '\\AdministratorModel';
        }

        if (!$container->has($requestedName)) {
            $container->setInvokableClass($requestedName, $requestedName, false);
        }

        return new $requestedName;
    }
}
