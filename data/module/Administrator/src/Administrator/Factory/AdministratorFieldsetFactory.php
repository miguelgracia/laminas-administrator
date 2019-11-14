<?php
namespace Administrator\Factory;

use Interop\Container\ContainerInterface;
use Zend\Db\Metadata\Source\Factory;
use Zend\Hydrator\ArraySerializable;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdministratorFieldsetFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $fieldset = (new $requestedName($requestedName));

        return $fieldset
            ->setServiceLocator($container)
            ->setHydrator(new ArraySerializable())
            ->setTableGateway($container->get($fieldset->getTableGatewayName()))
            ->setMetadata(Factory::createSourceFromAdapter($container->get('Zend\Db\Adapter\Adapter')));
    }
}