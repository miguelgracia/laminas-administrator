<?php
namespace Administrator\Factory;

use Administrator\Service\AdministratorFormService;
use Administrator\Service\SessionService;
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

        $tableGateway = preg_replace(
            '/^(Am)(\w+)\\\(\w+)\\\(\w+)(ModuleController)$/',
            "$1$2\\Model\\\\$2Table",
            $controller
        );

        if (!class_exists($tableGateway)) {
            throw new \Exception("Tablegateway $tableGateway not found");
        }

        return new $controller(
            $container->get(SessionService::class),
            $container->get($tableGateway),
            $container->get(AdministratorFormService::class)
        );
    }
}