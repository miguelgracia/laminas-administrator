<?php

namespace Application\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

class MenuDelegator implements DelegatorFactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        /** @var Menu $menuHelper */
        $menuHelper = call_user_func($callback);
        return $menuHelper->setActiveRouteName(
            $container->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName()
        );
    }
}
