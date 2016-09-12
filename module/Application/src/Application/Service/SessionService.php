<?php

namespace Application\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use \Zend\ServiceManager\ServiceLocatorInterface;


class SessionService implements FactoryInterface, SessionServiceInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->initSession(array(
            'remember_me_seconds' => 180,
            'use_cookies' => true,
            'cookie_httponly' => true,
        ));

        return new Container('frontend');
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