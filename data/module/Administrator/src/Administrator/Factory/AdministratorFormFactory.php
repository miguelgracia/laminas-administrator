<?php
namespace Administrator\Factory;

use Interop\Container\ContainerInterface;
use Zend\Filter\Word\DashToCamelCase;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdministratorFormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $form = new $requestedName;
        $form->setServiceLocator($container);
        return $form;
    }
}