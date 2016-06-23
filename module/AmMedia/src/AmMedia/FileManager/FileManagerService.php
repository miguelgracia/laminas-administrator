<?php

namespace AmMedia\FileManager;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FileManagerService implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        $baseConfig = $config['AmMedia']['FileManager'];

        return new LocalFilemanager($serviceLocator, $baseConfig);
    }
}