<?php

namespace Api\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

trait ApiServiceTrait
{
    protected $serviceLocator;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        $this->table       = $serviceLocator->get($this->table);
        $this->tableLocale = $serviceLocator->get($this->tableLocale);

        return $this;
    }
}