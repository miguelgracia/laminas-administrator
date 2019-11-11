<?php

namespace Administrator\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdministratorModelAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return (substr($requestedName, -5) === 'Model');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!class_exists($requestedName)) {
            // Vamos a montar el nombre dinámicamente,
            // solo conservamos "GestorModel", y si cambia la aplicación
            // no tenemos que tocar esto

            $trozos = explode('\\', $requestedName);
            // Sabemos que nos podemos guardar los dos primeros trozos del namespace
            $requestedName = $trozos[0]."\\".$trozos[1]."\\AdministratorModel";
        }

        if (!$container->has($requestedName)) {
            $container->setInvokableClass($requestedName, $requestedName, false);
        }

        return new $requestedName;
    }

}