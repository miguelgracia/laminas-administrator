<?php

namespace OpenGraph\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OpenGraphService implements FactoryInterface
{
    protected $serviceLocator;

    protected $facebook;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->serviceLocator = $container;

        $this->facebook = new \stdClass();

        return $this;
    }


    public function facebook()
    {
        return $this->facebook;
    }
}