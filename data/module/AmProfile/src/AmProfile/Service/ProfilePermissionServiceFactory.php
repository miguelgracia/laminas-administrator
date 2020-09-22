<?php

namespace AmProfile\Service;

use Administrator\Service\AuthService;
use AmModule\Service\ModuleService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ProfilePermissionServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $container->get(AuthService::class)->getUserData(),
            $container->get(ModuleService::class)->getControllerActionsModules()
        );
    }
}
