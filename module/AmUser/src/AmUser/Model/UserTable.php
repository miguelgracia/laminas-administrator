<?php

namespace AmUser\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class UserTable extends AdministratorTable
{
    public $table = 'gestor_usuarios';

    public function save(AdministratorModel $model)
    {
        $checkPassword =  (bool) $model->getCheckPassword();

        $model->password = md5($model->password);

        if (!$checkPassword and $model->id > 0) {
            unset($model->password);
        }

        return parent::save($model);
    }

    public function updateActivo($id,$activo)
    {
        $this->update(array('activo' => $activo),
            array('id' => $id)
        );
    }

    public function updateLogin($id,$login)
    {
        $this->update(array('login' => $login),
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
            'ultimo_login' => date('Y-m-d H:i:s')
        ),array(
            'login' => $userCheck,
            'password' => $passwordCheck
        ));
    }
}