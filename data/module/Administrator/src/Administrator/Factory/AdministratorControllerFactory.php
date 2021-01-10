<?php

namespace Administrator\Factory;

use Administrator\Service\AdministratorFormService;
use Administrator\Service\AuthService;
use Administrator\Service\DatatableService;
use Administrator\Service\SessionService;
use AmProfile\Service\ProfilePermissionService;
use Interop\Container\ContainerInterface;
use Laminas\Filter\Word\DashToCamelCase;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AdministratorControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $routeMatch = $container->get('Application')->getMvcEvent()->getRouteMatch();
        $module = $routeMatch->getParam('module');

        $moduleName = 'Am' . ((new DashToCamelCase)->filter($module));

        $controllerClassName = sprintf('%s\\Controller\\%sModuleController', $moduleName, $moduleName);

        $controllerInstance = new $controllerClassName(
            $container->get('Config'),
            $container->get(SessionService::class),
            $container->get(AuthService::class),
            $container->get(ProfilePermissionService::class),
            $container->get(DatatableService::class),
            $container->get('ViewRenderer')
        );

        if ($module === 'login') {
            return $controllerInstance;
        }

        $datatableConfigServiceName = $moduleName . '\\Service\\DatatableConfigService';
        $datatablePluginManager = $container->get('DatatablePluginManager');

        $controllerInstance->setFormService($container->get(AdministratorFormService::class));

        if ($datatablePluginManager->has($datatableConfigServiceName)) {
            $controllerInstance->setDatatableConfigService(
                $datatablePluginManager->get($datatableConfigServiceName)
            );
        }

        $tableGateway = preg_replace(
            '/^(Am)(\w+)\\\(\w+)\\\(\w+)(ModuleController)$/',
            '$1$2\\Model\\\$2Table',
            $controllerClassName
        );

        if (class_exists($tableGateway)) {
            /**
             * el objeto de tipo tableGateway no la podemos setear por defecto en el constructor
             * ya que el modulo de login no tiene TableGateway asociado
             */

            $controllerInstance->setTableGateway($container->get($tableGateway));
        }

        return $controllerInstance;
    }
}
