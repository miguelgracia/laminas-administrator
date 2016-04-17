<?php

namespace Gestor\Model;

use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class GestorUsuariosTable extends GestorTable
{
    public $table = 'gestorusuarios';




    //  Grabar en la importación de Excel. Podría tratar de tirar de otros métodos pero estando medio enlazados con
    // forms y objetos no me cuadra, así que aunque sea guarreo, método nuevo
    public function GrabarExcel($unUsuario, $encryptKey, $clave)
    {
        // Vamos a adaptar pq si metemos todos los datos dice que si que ya
        $data = array(
            'login'            => $unUsuario['login'],
            'password'         => new Expression("AES_ENCRYPT('".$clave."','".$encryptKey."')"),
            'fechaAlta'        => date("Y-m-d H:i:s"),
            'idPerfil'         => $unUsuario['idPerfil'],
            'validado'         => $unUsuario['validado'],
            'activo'           => $unUsuario['activo'],
        );
        // Como lo tenemos en un array que es igualico que data,en realidad es muy fácil
        $idResultado = $this->save($data, 0);
        return $idResultado;
    }







    // Sacamos los usuarios de los perfiles que sean hijos del tuyo. Ya, ya, es un poco lío
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
                $select->join(array('p' => 'perfil'),
                    'p.id = gestorusuarios.idPerfil',
                    array('nombre'), $select::JOIN_INNER
                );
                $select->where(array('gestorusuarios.idPerfil' => $arrayPerfiles[$i]['id']));
            });
            $arrayTemporal = $resultSet->toArray();

            // Ahora lo sumamos
            $arrayFinal = array_merge($arrayFinal, $arrayTemporal);
        }

        //Capturamos el objecto ResultSet y le pasamos el array con la información necesaria.
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
        $select->from($this->table);
        $select->columns(array(
            'id',
            'login',
            'password' => ($decryptKey ? new Expression("AES_DECRYPT(password,'$decryptKey')") : 'password'),
            'fechaAlta',
            'ultimoLogin',
            'idPerfil',
            'validado'
        ));
        $select->where(array(
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
            $select->join('perfil','idPerfil = perfil.id','nombre', $select::JOIN_LEFT);
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

    public function getGestorUsuariosFromLP($login, $password)
    {
        $rowset = $this->select(array('login' => $login, 'password' => md5($password)));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row");
        }
        return $row;
    }

    public function saveGestorUsuarios(GestorUsuariosModel $gestorUsuarios)
    {
        $data = array(
            'login'     => $gestorUsuarios->login,
            'password'  => $gestorUsuarios->password,
            'idPerfil'  => $gestorUsuarios->idPerfil,
            'validado' => $gestorUsuarios->validado
        );

        $id = (int) $gestorUsuarios->id;

        if ($id == 0) {
            $data['fechaAlta'] = date('Y-m-d H:i:s');
        }

        if ($data['validado'] == null) { $data['validado'] = 0; } // Porque interpreta 0 como null y no es asi

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
            'ultimoLogin' => date('Y-m-d H:i:s')
        ),array(
            'login' => $userCheck,
            'password' => $passwordCheck
        ));
    }
}