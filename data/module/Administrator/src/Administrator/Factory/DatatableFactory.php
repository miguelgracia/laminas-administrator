<?php

namespace Administrator\Factory;

use Administrator\Service\DatatableService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class DatatableFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DatatableService(
            $container->get('Zend\Db\Adapter\Adapter'),
            $container->get('Translator')
        );
    }
}
