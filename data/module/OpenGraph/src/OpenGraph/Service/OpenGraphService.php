<?php

namespace OpenGraph\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OpenGraphService implements FactoryInterface
{
    protected $serviceLocator;

    protected $facebook;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $this->facebook = new \stdClass();

        return $this;
    }

    public function facebook()
    {
        return $this->facebook;
    }
}