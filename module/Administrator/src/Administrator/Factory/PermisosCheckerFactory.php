<?php
namespace Administrator\Factory;

use Administrator\Service\PermisosChecker;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PermisosCheckerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sm = $serviceLocator;

        $sessionService = $sm->get('Administrator\Service\SessionServiceInterface');
        $authService = $sm->get('AuthService');

        $identity = $authService->getIdentity();

        $dataUser = $sessionService->getUserData($identity['user']);

        return new PermisosChecker($sm,$dataUser);
    }
}