<?php

namespace Administrator\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;

class AuthService implements FactoryInterface
{
    protected $serviceLocator;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return $this
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        //My assumption, you've alredy set dbAdapter
        //and has users table with columns : user_name and pass_word
        //that password hashed with md5
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        $dbTableAuthAdapter  = new  AuthAdapter($dbAdapter,
            'gestor_usuarios','login','password', "md5(?)");

        $authService = new AuthenticationService();
        $authService->setAdapter($dbTableAuthAdapter);
        $authService->setStorage($serviceLocator->get('Administrator\Model\AuthStorage'));

        return $authService;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}