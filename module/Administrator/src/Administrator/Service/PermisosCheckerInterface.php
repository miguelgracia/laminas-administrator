<?php

namespace Administrator\Service;

interface PermisosCheckerInterface
{
    public function __construct($sm, $idPerfil);

    public function isAdmin();

    public function hasModuleAccess($module, $action);

    public function redibujarMenu($dataMenuTemp);

}