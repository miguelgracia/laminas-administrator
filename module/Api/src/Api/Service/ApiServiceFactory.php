<?php

namespace Api\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApiServiceFactory implements FactoryInterface
{
    private $serviceLocator;

    private $allowedServices = [
        'AppData',
        'Blog',
        'BlogCategory',
        'HomeModule',
        'Job',
        'JobCategory',
        'Language',
        'Megabanner',
        'Partner',
        'Section',
        'StaticPage',
    ];

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param $name string
     * @return mixed
     */
    function __get($service)
    {
        $service = ucfirst($service);

        if (in_array($service, $this->allowedServices)) {
            $serviceName = sprintf('Application\Api\\%s', $service);
            return $this->serviceLocator->get($serviceName);
        }

        throw new \Exception('Api Services Not Exist');
    }
}