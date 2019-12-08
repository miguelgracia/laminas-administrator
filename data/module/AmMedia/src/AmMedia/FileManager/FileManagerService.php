<?php

namespace AmMedia\FileManager;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class FileManagerService implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        $baseConfig = $config['AmMedia']['FileManager'];

        return new LocalFilemanager($container, $baseConfig);
    }
}
