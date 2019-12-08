<?php

namespace Administrator\Factory;

use AmProfile\Service\ProfilePermissionService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class DatatableConfigFactory implements FactoryInterface
{
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
        return new $requestedName(
            $container->get('ControllerPluginManager'),
            $container->get(ProfilePermissionService::class),
            $container->get('Translator')
        );
    }

}