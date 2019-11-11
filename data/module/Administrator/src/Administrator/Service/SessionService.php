<?php

namespace Administrator\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class SessionService implements FactoryInterface, SessionServiceInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->initSession(array(
            'remember_me_seconds' => 180,
            'use_cookies' => true,
            'cookie_httponly' => true,
        ));

        return new Container('administrator');
    }

    public function initSession(Array $config)
    {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config);
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->start();
        Container::setDefaultManager($sessionManager);
    }
}