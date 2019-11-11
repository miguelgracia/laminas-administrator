<?php

namespace Administrator\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DatatableConfigAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @return void
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return (substr($requestedName, -22) === 'DatatableConfigService');
    }

    /**
     * Create service with name
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!$container->has($requestedName)) {
            $container->setInvokableClass($requestedName, $requestedName);
        }

        return (new $requestedName)->setServiceLocator($container);
    }

}