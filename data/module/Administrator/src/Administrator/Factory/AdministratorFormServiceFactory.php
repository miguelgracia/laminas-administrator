<?php

namespace Administrator\Factory;

use Administrator\Service\AdministratorFormService;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AdministratorFormServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return (new AdministratorFormService(
            $container->get('FormElementManager')
        ));
    }
}
