<?php

namespace Administrator\Factory;

use Administrator\Model\AuthStorage;
use Administrator\Service\AuthService;
use AmUser\Model\UserTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;

class AuthFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AuthService(
            new AuthenticationService(
                $container->get(AuthStorage::class),
                new AuthAdapter(
                    $container->get(Adapter::class),
                    'admin_users',
                    'username',
                    'password',
                    'md5(?)'
                )
            ),
            $container->get(UserTable::class)
        );
    }
}
