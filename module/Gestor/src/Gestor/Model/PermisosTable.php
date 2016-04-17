<?php

namespace Gestor\Model;

class PermisosTable extends GestorTable
{
    protected $table = "gestorpermisos";


    public function fetchAllPerfilBasic($idPerfil,$idTipoRecurso = 1)
    {
        // Resulset contendrá un objecto de tipo Gestor\Model\GestorResultSet
        $resultSet = $this->select(array(
            'idPerfil'      => $idPerfil,
            'idTipoRecurso' => $idTipoRecurso
        ));

        return $resultSet;
        //return $resultSet->toArray();
    }



    public function fetchAllPerfil($idPerfil,$idTipoRecurso = 1)
    {
        // Resulset contendrá un objecto de tipo Gestor\Model\GestorResultSet
        $resultSet = $this->select(array(
            'idPerfil'      => $idPerfil,
            'idTipoRecurso' => $idTipoRecurso
        ));

        // seteamos la key principal y secundaría del array que se formará cuando llamemos a la
        // función toArray de nuestro Resulset

        $resultSet->setFetchGroupResultSet('idPerfil','idRecurso');

        /**
         * Como vamos a tener que iterar, devolvemos el contenido de resultset en un array
         * Tendrá un formato similar al siguiente (teniendo en cuenta que hemos seteado las
         * keys principal y secundaria)
         *
         *
         *    Array
         *    (
         *         [VALOR DE KEY PRINCIPAL] => Array
         *             (
         *                 [VALOR DE KEY SECUNDARIA] => Array
         *                 (
         *                     [id] => 62
         *                     [idPerfil] => 2
         *                     [idRecurso] => 1
         *                     [idTipoRecurso] => 1
         *                 ),
         *                 ...
         *         ),
         *         ...
         *     )
         */
        return $resultSet->toArray();
    }

    public function deleteAllPerfil($idPerfil)
    {
        $this->delete(array('idPerfil' => (int) $idPerfil));
    }

    public function insertPermiso($idPerfil, $idRecurso)
    {
        $data = array(
            'idPerfil' => $idPerfil,
            'idRecurso'  => $idRecurso,
        );
        $this->insert($data);
    }
}