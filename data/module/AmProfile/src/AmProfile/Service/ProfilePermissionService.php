<?php

namespace AmProfile\Service;

use Laminas\Filter\Word\DashToCamelCase;

class ProfilePermissionService
{
    protected $userData;

    protected $controllerActionModules;

    public function __construct($userData, $controllerActionModules)
    {
        $this->userData = $userData;
        $this->controllerActionModules = $controllerActionModules;
    }

    public function isAdmin()
    {
        return (bool) $this->userData->isAdmin;
    }

    /**
     * @param $controller
     * @param $action
     * @return mixed
     * @throws \Exception
     *
     * Devuelve 1 o 0 en función de si tiene o no tiene permiso de acceso. False si la ruta a comprobar no existe
     */
    public function hasModuleAccess($controller, $action)
    {
        $action = lcfirst((new DashToCamelCase)->filter($action));

        if (!array_key_exists($controller . '.' . $action, $this->controllerActionModules)) {
            return false;
        }

        if ($this->isAdmin()) {
            return 1;
        }

        $hasModuleAccess = in_array($controller . '.' . $action, json_decode($this->userData->permissions));

        return (int) $hasModuleAccess;
    }

    public function redibujarMenu(&$dataMenu)
    {
        if ($this->isAdmin()) {
            return $dataMenu;
        }

        foreach ($dataMenu as $i => $menuTemp) {
            if ($menuTemp->zendName != '' and !$this->hasModuleAccess($menuTemp->zendName, $menuTemp->action)) {
                /**
                 * El usuario no tiene permisos de acceso. Eliminamos el registro del array
                 */
                unset($dataMenu[$i]);

                /**
                 * no es necesario comprobar si hay acceso a los hijos porque directamente
                 * no hay acceso al padre, así que continuamos con la siguiente iteración.
                 */

                continue;
            }

            foreach ($menuTemp->hijos as $indexHijo => $hijo) {
                if ($hijo->action === '' or $this->hasModuleAccess($hijo->zendName, $hijo->action)) {
                    continue;
                }

                /**
                 * El usuario no tiene permisos de acceso. Eliminamos el registro del array
                 */
                unset($menuTemp->hijos[$indexHijo]);
            }
        }

        return $dataMenu;
    }
}
