<?php

namespace AmMenu\Factory;

use AmMenu\Navigation\MenuNavigation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class MenuNavigationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return (new MenuNavigation())->createService($container);
    }
}
