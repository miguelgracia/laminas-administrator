<?php

namespace Administrator\Service;

use Zend\Db\Sql\Select;

class AuthService
{
    protected $userData = false;
    protected $authService;
    protected $userTable;

    public function __construct($authService, $userTable)
    {
        $this->authService = $authService;
        $this->userTable = $userTable;
    }

    public function getAuthInstance()
    {
        return $this->authService;
    }

    /**
     * @return mixed
     */
    public function getUserData()
    {
        if ($this->userData) {
            return $this->userData;
        }

        $username = $this->authService->getIdentity();

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
