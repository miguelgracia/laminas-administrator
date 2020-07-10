<?php

namespace Administrator\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

class AdministratorTableAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return (substr($requestedName, -5) === 'Table');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!$container->has($requestedName)) {
            $container->setInvokableClass($requestedName, $requestedName);
        }

        return new $requestedName;
    }
}
