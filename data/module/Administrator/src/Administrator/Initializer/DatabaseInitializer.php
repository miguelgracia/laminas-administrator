<?php

namespace Administrator\Initializer;

use Administrator\Model\AdministratorResultSet;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Hydrator\ClassMethods;
use Laminas\ServiceManager\Initializer\InitializerInterface;

class DatabaseInitializer implements InitializerInterface
{
    public function __invoke(ContainerInterface $container, $instance)
    {
        //Seteamos el dbAdapter y el resultset para todos los modelos)
        if ($instance instanceof \Laminas\Db\Adapter\AdapterAwareInterface) {
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
