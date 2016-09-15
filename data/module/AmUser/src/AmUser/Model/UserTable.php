<?php

namespace AmUser\Model;

use Administrator\Model\AdministratorTable;
use Zend\Db\Sql\Expression;

class UserTable extends AdministratorTable
{
    public $table = 'admin_users';

    protected $entityModelName =  UserModel::class;

    public function save($model, $id = 0, $fieldKey = 'id')
    {
        $checkPassword =  (bool) $model->getCheckPassword();

        $model->password = md5($model->password);

        if (!$checkPassword and $model->id > 0) {
            unset($model->password);
        }

        return parent::save($model, $id, $fieldKey);
    }

    public function updateActivo($id,$active)
    {
        $this->update(array('active' => $active),
            array('id' => $id)
        );
    }

    public function updateLogin($id,$username)
    {
        $this->update(array('username' => $username),
            array('id' => $id)
        );
    }

    public function updatePassword($id,$password,$decryptKey)
    {
        $this->update(array('password' => new Expression("AES_ENCRYPT('$password','$decryptKey')")),
                      array('id' => $id)
        );
    }

    public function deleteGestorUsuarios($id)
    {
        $this->delete(array('id' => (int) $id));
    }

    /**
     * Actualizamos manualmente el ultimo login en nuestra tabla
     *
     * @param $userCheck
     * @param $passwordCheck
     * @param $dbAdapter
     */

    public function updateLastLogin($userCheck, $passwordCheck)
    {
        $this->update(array(
            'last_login' => date('Y-m-d H:i:s')
        ),array(
            'username' => $userCheck,
            'password' => $passwordCheck
        ));
    }
}