<?php

namespace Api\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

trait ApiServiceTrait
{
    protected $serviceLocator;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        if (isset($this->table)) {
            $this->table       = $serviceLocator->get($this->table);
        }
        if (isset($this->tableLocale)) {
            $this->tableLocale = $serviceLocator->get($this->tableLocale);
        }

        return $this;
    }
}