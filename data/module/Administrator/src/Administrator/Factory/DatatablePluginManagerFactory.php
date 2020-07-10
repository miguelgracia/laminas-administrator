<?php

namespace Administrator\Factory;

use Administrator\Service\DatatablePluginManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DatatablePluginManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        return new DatatablePluginManager($container, $config['datatable'] ?? []);
    }
}
