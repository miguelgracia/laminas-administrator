<?php

namespace AmMenu\Factory;

use AmMenu\Navigation\MenuNavigation;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MenuNavigationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $navigation = new MenuNavigation();
        return $navigation->createService($container);
    }
}