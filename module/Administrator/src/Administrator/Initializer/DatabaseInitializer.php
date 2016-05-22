<?php

namespace Administrator\Initializer;


use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DatabaseInitializer implements InitializerInterface
{
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        //Seteamos el dbAdapter para todos los modelos)
        if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
            $instance->setServiceLocator($serviceLocator);
            $instance->setDbAdapter($serviceLocator->get('Zend\Db\Adapter\Adapter'));
        }
    }

}