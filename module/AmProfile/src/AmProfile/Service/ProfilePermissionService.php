<?php
namespace AmProfile\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProfilePermissionService implements FactoryInterface
{
    protected $userData;

    protected $isSuperUser;

    protected $sm;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->sm = $serviceLocator;

        $authService = $this->sm->get('AuthService');

        $dataUser = $authService->getUserData();

        $this->userData = $dataUser;
        $this->isSuperUser = (bool) $this->userData->esAdmin;

        return $this;
    }

    public function isAdmin()
    {
        return $this->isSuperUser;
    }

    public function hasModuleAccess($controller, $action)
    {
        if ($this->isSuperUser) {
            return true;
        }

        $permisos = json_decode($this->userData->permisos);

        $tienePermiso = in_array($controller.'.'.$action,$permisos);

        return $tienePermiso;
    }

    public function redibujarMenu(&$dataMenu)
    {
        if ($this->isSuperUser) {
            return $dataMenu;
        }

        foreach ($dataMenu as $i => $menuTemp) {

            if ($menuTemp->nombreZend != '') {
                if (!$this->hasModuleAccess($menuTemp->nombreZend,$menuTemp->accion)) {
                    //El usuario no tiene permisos de acceso. Eliminamos el registro del array
                    unset($dataMenu[$i]);
                    //no es necesario comprobar si hay acceso a los hijos porque directamente
                    //no hay acceso al padre, as� que continuamos con la siguiente iteraci�n.

                    continue;
                }
            }

            foreach($menuTemp->hijos as $indexHijo => $hijo) {
                if ($hijo->accion != '') {
                    if (!$this->hasModuleAccess($hijo->nombreZend, $hijo->accion)) {
                        //El usuario no tiene permisos de acceso. Eliminamos el registro del array
                        unset($menuTemp->hijos[$indexHijo]);
                    }
                }
            }
        }

        return $dataMenu;
    }
}