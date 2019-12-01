<?php
namespace Administrator\Factory;

use Administrator\Service\AdministratorFormService;
use Administrator\Service\DatatableService;
use Administrator\Service\SessionService;
use AmProfile\Service\ProfilePermissionService;
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

        $controller =  new $controller(
            $container->get(SessionService::class),
            $container->get(ProfilePermissionService::class),
            $container->get(AdministratorFormService::class),
            $container->get(DatatableService::class),
            $container->get('ViewRenderer')
        );

        /**
         * el objeto de tipo tableGateway no la podemos setear por defecto en el constructor
         * ya que el modulo de login no tiene TableGateway asociado
         */
        if ($module !== 'login') {
            if (!class_exists($tableGateway)) {
                $message = "Tablegateway $tableGateway not found";
                var_dump($message);
                die;

                /**
                 * TODO: Si lanzamos la excepcion, la pantalla se queda en blanco y no vemos qué pasa.
                 * hay que ver la forma de mostar el mensaje por pantalla
                 */

                throw new \Exception($message);
            } else {
                $controller->setTableGateway($container->get($tableGateway));
            }
        }

        return $controller;
    }
}