<?php

namespace Application\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ApplicationHelperFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName($container);
    }

}