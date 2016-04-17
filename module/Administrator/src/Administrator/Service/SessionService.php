<?php

namespace Administrator\Service;

use Zend\Db\Sql\Select;
use Zend\ServiceManager\FactoryInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use \Zend\ServiceManager\ServiceLocatorInterface;


class SessionService implements FactoryInterface, SessionServiceInterface
{
    protected $dbAdapter;
    protected $usuariosTableGateway;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        $this->usuariosTableGateway = $serviceLocator->get('Administrator\Model\AdminUserTable');

        return $this;
    }

    public function initSession(Array $config)
    {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config['session']);
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->start();
    }

    /**
     *
     * @param $username
     * @return mixed
     */
    public function getUserData($username)
    {
        $rowset = $this->usuariosTableGateway->select(function(Select $select) use($username) {
            $select->join(
                'admin_groups',
                'admin_groups.id = admin_users.admin_group_id',
                array(
                    'group_permissions' => 'permissions',
                    'is_superuser'
                )
            )->where(array(
                'email' => $username
            ));
        });
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row");
        }
        return $row;
    }
}