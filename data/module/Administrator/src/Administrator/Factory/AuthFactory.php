<?php

namespace Administrator\Factory;

use Administrator\Service\AuthService;
use AmUser\Model\UserTable;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\Storage\Session;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Laminas\Session\SessionManager;

class AuthFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager = new SessionManager();

        $storage = new Session(
            Session::NAMESPACE_DEFAULT,
            Session::MEMBER_DEFAULT,
            $sessionManager->rememberMe(10)
        );

        return new AuthService(
            new AuthenticationService(
                $storage,
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
