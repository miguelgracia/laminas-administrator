<?php

namespace Administrator\Factory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdministratorModelAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
        return (substr($requestedName, -5) === 'Model');
    }

    public function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
        if (!class_exists($requestedName)) {
            // Vamos a montar el nombre dinámicamente,
            // solo conservamos "GestorModel", y si cambia la aplicación
            // no tenemos que tocar esto

            $trozos = explode('\\', $requestedName);
            // Sabemos que nos podemos guardar los dos primeros trozos del namespace
            $requestedName = $trozos[0]."\\".$trozos[1]."\\AdministratorModel";
        }

        if (!$locator->has($requestedName)) {
            $locator->setInvokableClass($requestedName, $requestedName, false);
        }

        return $locator->create($requestedName);
    }
}