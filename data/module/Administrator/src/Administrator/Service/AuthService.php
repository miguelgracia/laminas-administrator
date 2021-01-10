<?php

namespace Administrator\Service;

use Laminas\Authentication\Adapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage;
use Laminas\Db\Sql\Select;

class AuthService extends AuthenticationService
{
    protected $userData = false;
    protected $userTable;

    public function __construct($userTable, Storage\StorageInterface $storage = null, Adapter\AdapterInterface $adapter = null)
    {
        parent::__construct($storage, $adapter);
        $this->userTable = $userTable;
    }

    public function checkUser($username, $password)
    {
        $this
            ->getAdapter()
            ->setIdentity($username)
            ->setCredential($password);

        $result = $this->authenticate();

        $this->getStorage()->write([
            'user' => $username,
            'password' => $password
        ]);

        if ($result->getCode() != 1) {
            //Forbid User
            $this->clearIdentity();
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getUserData()
    {
        if ($this->userData) {
            return $this->userData;
        }

        $username = $this->getIdentity();

        $username = $username['user'];

        $rowSet = $this->userTable->select(function (Select $select) use ($username) {
            $select
                ->columns(['*'])
                ->join(
                    'admin_profiles',
                    'admin_profiles.id = admin_users.admin_profile_id',
                    ['is_admin', 'permissions', 'key']
                )
                ->where([
                    'username' => $username
                ]);
        });

        $row = $rowSet->current();

        $this->userData = $row ?: false;

        return $this->userData;
    }
}
