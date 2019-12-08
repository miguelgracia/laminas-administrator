<?php

namespace Administrator\Initializer;

use Administrator\Model\AdministratorResultSet;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Initializer\InitializerInterface;

class DatabaseInitializer implements InitializerInterface
{
    public function __invoke(ContainerInterface $container, $instance)
    {
        //Seteamos el dbAdapter y el resultset para todos los modelos)
        if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
            $resultSet = (new AdministratorResultSet)
                ->setHydrator(new ClassMethods)
                ->setObjectPrototype($container->get($instance::ENTITY_MODEL_CLASS));

            $instance
                ->setResultSetPrototype($resultSet)
                ->setDbAdapter($container->get(Adapter::class))
                ->initialize();
        }
    }
}
