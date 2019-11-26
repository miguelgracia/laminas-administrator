<?php
namespace AmProfile\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ProfilePermissionServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $container->get('AuthService')->getUserData(),
            $container->get('AmModule\Service\ModuleService')->getControllerActionsModules()
        );
    }
}