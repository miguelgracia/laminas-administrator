<?php

namespace Administrator\Service;

class PermisosChecker implements PermisosCheckerInterface
{
    protected $userData;

    protected $isSuperUser;

    function __construct($sm, $userData)
    {
        $this->sm = $sm;
        $this->userData = $userData;
        $this->isSuperUser = $this->userData->isSuperuser == '1';
    }

    public function isAdmin()
    {
        return $this->isSuperUser;
    }

    public function hasModuleAccess($module, $action)
    {
        if ($this->isSuperUser) {
            return true;
        }

        // Vamos a comprobar si el $idPerfil tiene permisos para el $module.

        // 0. Sacamos la parte final del controlador y en minusculas
        $partirModule = explode('\\', $module);

        $moduleFinal = strtolower($partirModule[count($partirModule) - 1]);

        // 1. Buscamos $module como texto en la tabla de controladores
        $modules = $this->sm->get('Administrator\Model\ModuleTable')->select();

        $moduleExists = false;

        foreach ($modules as $module)
        {
            if (strtolower($module->name) == $moduleFinal) {
                $moduleExists = true;
                //$idEncontrado = $module->id;
            }
        }

        if (!$moduleExists)
        {
            // Si no se ha encontrado el módulo entre los listados, no vamos a dejarle
            return false;
        } else {

            $groupPermissions = json_decode($this->userData->groupPermissions,true);
            $userPermissions = json_decode($this->userData->permissions,true);

            $permissions = array_merge($groupPermissions, $userPermissions);

            $permissionKey = $moduleFinal.'.'.$action;

            //TODO: Barajar la opción de devolver una excepción cuando el permiso no se encuentre definido
            return array_key_exists($permissionKey, $permissions) and ((bool)$permissions[$permissionKey]);
        }
    }

    public function redibujarMenu($dataMenuTemp)
    {
        if ($this->isSuperUser) {
            return $dataMenuTemp;
        }

        foreach ($dataMenuTemp as $i => $menuTemp) {

            if ($menuTemp->nombreZend != '') {
                if (!$this->hasModuleAccess($menuTemp->nombreZend)) {
                    //El usuario no tiene permisos de acceso. Eliminamos el registro del array
                    unset($dataMenuTemp[$i]);
                    //no es necesario comprobar si hay acceso a los hijos porque directamente
                    //no hay acceso al padre, as� que continuamos con la siguiente iteraci�n.

                    continue;
                }
            }

            foreach($menuTemp->hijos as $indexHijo => $hijo) {
                if ($hijo->tieneEnlace == 1) {
                    if (!$this->hasControllerAccess($hijo->nombreZend)) {
                        //El usuario no tiene permisos de acceso. Eliminamos el registro del array
                        unset($menuTemp->hijos[$indexHijo]);
                    }
                }
            }
        }
        return $dataMenuTemp;
    }
}