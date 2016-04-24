<?php

namespace AmUser\Model;

use Administrator\Model\AdministratorTable;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class UserTable extends AdministratorTable
{
    public $table = 'gestor_usuarios';

    public function CuantosLogin($login)
    {
        $select = new Select();

        $select->from($this->table)->columns(array(
            "cuantos" => new Expression("count(*)")
        ))->where(array(
            'login' => $login
        ));

        $resultSet = $this->selectWith($select);

        $row = $resultSet->current();

        return $row->cuantos;
    }

    // Sacamos los usuarios de los perfiles que sean hijos del tuyo. Ya, ya, es un poco l�o
    public function fetchHijos($idPerfil, $perfilTable)
    {
        // Primero sacamos todos los perfiles del usuario que accede o sus inferiores
        $arrayPerfiles = $perfilTable->fetchHijos($idPerfil);
        $arrayYo = $perfilTable->fetchId($idPerfil);

        $arrayPerfiles = array_merge($arrayPerfiles->toArray(), $arrayYo->toArray());

        $arrayFinal = array();
        for ($i = 0; $i < count($arrayPerfiles); $i++)
        {
            // Ahora para cada uno de estos $arrayPerfiles[$i]['id'] sacamos los usuarios
            $resultSet = $this->select(function(Select $select) use ($idPerfil, $arrayPerfiles, $i) {
                $select->join(array('p' => 'gestor_perfil_id'),
                    'p.id = gestor_usuarios.gestor_perfil_id',
                    array('nombre'), $select::JOIN_INNER
                );
                $select->where(array('gestor_usuarios.gestor_perfil_id' => $arrayPerfiles[$i]['id']));
            });
            $arrayTemporal = $resultSet->toArray();

            // Ahora lo sumamos
            $arrayFinal = array_merge($arrayFinal, $arrayTemporal);
        }

        //Capturamos el objecto ResultSet y le pasamos el array con la informaci�n necesaria.
        //De esta forma no perdemos la funcionalidad que nos proporciona dicho objeto

        $resultSet = $this->getResultSetPrototype();
        return $resultSet->initialize($arrayFinal);
    }

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

    // Sacamos todos los usuarios
    public function fetchAll()
    {
        $resultSet = $this->select(function(Select $select){
            $select->join('gestor_perfiles','gestor_perfil_id = gestor_perfiles.id','nombre', $select::JOIN_LEFT);
        });

        return $resultSet;
    }

    // Sacamos un id de usuario
    public function getGestorUsuarios($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveGestorUsuarios(GestorUsuariosModel $gestorUsuarios)
    {
        $data = array(
            'login'             => $gestorUsuarios->login,
            'password'          => $gestorUsuarios->password,
            'gestor_perfil_id'  => $gestorUsuarios->gestorPerfilId,
            'validado'          => $gestorUsuarios->validado
        );

        $id = (int) $gestorUsuarios->id;

        return $this->save($data,$id);
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