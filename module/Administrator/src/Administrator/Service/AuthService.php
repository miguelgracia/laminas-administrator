<?php

namespace Administrator\Service;

use Zend\Db\Sql\Select;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;

class AuthService implements FactoryInterface
{
    protected $serviceLocator;
    protected $userData = false;
    protected $authService;

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
            'admin_users','username','password', "md5(?)");

        $storage = $serviceLocator->get('Administrator\Model\AuthStorage');

        $this->authService = new AuthenticationService($storage, $dbTableAuthAdapter);

        return $this;
    }

    public function getAuthInstance()
    {
        return $this->authService;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     *
     * @param $username
     * @return mixed
     */
    public function getUserData()
    {
        if ($this->userData) {
            return $this->userData;
        }

        $username = $this->authService->getIdentity();

        $username = $username['user'];

        $rowset = $this->serviceLocator->get('AmUser\Model\UserTable')->select(function (Select $select) use($username) {
            $select
                ->columns(array('*'))
                ->join(
                    'admin_profiles',
                    'admin_profiles.id = admin_users.admin_profile_id',
                    array('is_admin','permissions','key')
                )
                ->where(array(
                    'username' => $username
                ));
        });

        $row = $rowset->current();

        $this->userData = $row ?: false;

        return $this->userData;
    }
}