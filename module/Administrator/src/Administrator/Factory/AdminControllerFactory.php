<?php
namespace Administrator\Factory;

use Zend\Filter\Word\DashToCamelCase;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sm = $serviceLocator->getServiceLocator();

        $routeMatch = $sm->get('Application')->getMvcEvent()->getRouteMatch();
        $module = $routeMatch->getParam('module');

        $module = $module == "" ? "login" : $module;

        $dashToCamel = new DashToCamelCase();

        $moduleName = 'Am'.$dashToCamel->filter($module);

        $namespace = $moduleName."\\Controller\\".$moduleName."ModuleController";

        $controller = $serviceLocator->get($namespace);

        $controller->setControllerVars();

        return $controller;
    }

}