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
            'gestor_usuarios','login','password', "md5(?)");

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
                    'gestor_perfiles',
                    'gestor_perfiles.id = gestor_usuarios.gestor_perfil_id',
                    array('es_admin','permisos','key')
                )
                ->where(array(
                    'login' => $username
                ));
        });

        $row = $rowset->current();

        if (!$row) {
            throw new \Exception("Could not find row");
        }

        $this->userData = $row;

        return $row;
    }
}