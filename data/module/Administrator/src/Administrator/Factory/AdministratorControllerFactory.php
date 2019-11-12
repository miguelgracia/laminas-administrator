<?php
namespace Administrator\Factory;

use Interop\Container\ContainerInterface;
use Zend\Filter\Word\DashToCamelCase;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdministratorControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $routeMatch = $container->get('Application')->getMvcEvent()->getRouteMatch();
        $module = $routeMatch->getParam('module');

        $module = $module == "" ? "login" : $module;

        $moduleName = 'Am'.((new DashToCamelCase)->filter($module));

        $controller = sprintf("%s\\Controller\\%sModuleController", $moduleName, $moduleName);

        return new $controller;
    }
}