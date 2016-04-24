<?php

namespace Administrator\Model;

class PermisosTable extends AdministratorTable
{
    protected $table = "gestor_permisos";

    public function fetchAllPerfilBasic($idPerfil)
    {
        // Resulset contendr� un objecto de tipo Gestor\Model\GestorResultSet
        $resultSet = $this->select(array(
            'gestor_perfil_id'      => $idPerfil
        ));

        return $resultSet;
    }

    public function fetchAllPerfil($idPerfil)
    {
        // Resulset contendr� un objecto de tipo Gestor\Model\GestorResultSet
        $resultSet = $this->select(array(
            'gestor_perfil_id' => $idPerfil
        ));

        // seteamos la key principal y secundar�a del array que se formar� cuando llamemos a la
        // funci�n toArray de nuestro Resulset

        $resultSet->setFetchGroupResultSet('gestorPerfilId','gestorControladorId');

        /**
         * Como vamos a tener que iterar, devolvemos el contenido de resultset en un array
         * Tendr� un formato similar al siguiente (teniendo en cuenta que hemos seteado las
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
        $this->delete(array('gestor_perfil_id' => (int) $idPerfil));
    }

    public function insertPermiso($idPerfil, $idControlador)
    {
        $data = array(
            'gestor_perfil_id' => $idPerfil,
            'gestor_controlador_id'  => $idControlador,
        );
        $this->insert($data);
    }
}