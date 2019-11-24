<?php


namespace Administrator\Factory;

use Administrator\Service\AdministratorFormService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdministratorFormServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $routeParams = $container->get('Application')->getMvcEvent()->getRouteMatch()->getParams();

        return (new AdministratorFormService(
            $container->get('FormElementManager'),
            $routeParams
        ))->setActionType($routeParams['action']);
    }

}