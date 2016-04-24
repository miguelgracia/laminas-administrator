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
        $this->isSuperUser = (bool) $this->userData->esAdmin;
    }

    public function isAdmin()
    {
        return $this->isSuperUser;
    }

    public function hasModuleAccess($controlador, $accion)
    {
        if ($this->isSuperUser) {
            return true;
        }
        // Vamos a comprobar si el $gestorPerfilId tiene permisos para el $controlador.

        // 0. Sacamos la parte final del controlador y en minusculas

        $partirControlador = explode('\\', $controlador);
        $controladorFinal = strtolower($partirControlador[count($partirControlador) - 1]);

        // 1. Buscamos $controlador como texto en la tabla de controladores
        $arrayControladores = $this->sm->get('Administrator\Model\GestorControladorTable')->select();
        $encontrado = false;
        $idEncontrado = 0;

        foreach ($arrayControladores as $elemento)
        {
            if ($elemento->nombreZend == $controladorFinal) {
                $encontrado = true;
                $idEncontrado = $elemento->id;
            }
        }

        if (!$encontrado) {
            // Si no se ha encontrado el controlador entre los listados, no vamos a dejarle
            return false;
        } else {
            // Buscamos si el id del controlador existe para este gestorPerfilId

            $listaPermisos = $this->sm->get('Administrator\Model\PermisosTable')->fetchAllPerfilBasic($this->userData->gestorPerfilId);

            $tienePermiso = false;

            foreach ($listaPermisos as $permiso)
            {
                if ($permiso->gestorControladorId == $idEncontrado)
                {
                    $tienePermiso = true;
                    break;
                }
            }

            return $tienePermiso;
        }
    }

    public function redibujarMenu(&$dataMenu)
    {
        if ($this->isSuperUser) {
            return $dataMenu;
        }

        foreach ($dataMenu as $i => $menuTemp) {

            if ($menuTemp->nombreZend != '') {
                if (!$this->hasModuleAccess($menuTemp->nombreZend)) {
                    //El usuario no tiene permisos de acceso. Eliminamos el registro del array
                    unset($dataMenu[$i]);
                    //no es necesario comprobar si hay acceso a los hijos porque directamente
                    //no hay acceso al padre, así que continuamos con la siguiente iteración.

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

        return $dataMenu;
    }
}