<?php

namespace Gestor\Service;

class PermisosCheckerAdmin implements PermisosCheckerInterface
{
    function __construct($sm, $idPerfil)
    {
        $this->sm = $sm;
        $this->idPerfil = $idPerfil;
    }

    public function isAdmin()
    {
        return true;
    }

    public function hasControllerAccess($controlador)
    {
        return true;
    }

    public function RedibujarMenu($dataMenuTemp)
    {
        // En admin lo devolvemos tal cual
        return $dataMenuTemp;
    }
}