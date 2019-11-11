<?php

namespace Administrator\Initializer;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Initializer\InitializerInterface;

class DatabaseInitializer implements InitializerInterface
{
    public function __invoke(ContainerInterface $container, $instance)
    {
        //Seteamos el dbAdapter para todos los modelos)
        if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
            $instance->setServiceLocator($container);
            $instance->setDbAdapter($container->get('Zend\Db\Adapter\Adapter'));
        }
    }
}