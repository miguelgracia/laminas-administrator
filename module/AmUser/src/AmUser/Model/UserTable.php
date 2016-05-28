<?php

namespace AmUser\Model;

use Administrator\Model\AdministratorTable;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class UserTable extends AdministratorTable
{
    public $table = 'gestor_usuarios';

    /**
     * Devuelve el password desencriptado de base de datos.
     *
     * @param array $whereFields. Array asociativo con los campos que el usuario introduce en el formulario
     */
    public function getUserdata($login, $decryptKey = false)
    {
        $select = new Select();
        $select->from($this->table)
            ->columns(array(
            'id',
            'login',
            'password' => ($decryptKey ? new Expression("AES_DECRYPT(password,'$decryptKey')") : 'password'),
            'fecha_alta',
            'ultimo_login',
            'gestor_perfil_id',
            'validado'
        ))->where(array(
            'login' => $login
        ));

        $resultSet = $this->selectWith($select);

        $row = $resultSet->current();

        return $row;
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