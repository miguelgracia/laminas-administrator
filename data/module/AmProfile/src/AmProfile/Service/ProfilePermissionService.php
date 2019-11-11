<?php
namespace AmProfile\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Filter\Word\DashToCamelCase;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProfilePermissionService implements FactoryInterface
{
    protected $userData;

    protected $isSuperUser;

    protected $serviceLocator;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->serviceLocator = $container;

        $authService = $this->serviceLocator->get('AuthService');

        $dataUser = $authService->getUserData();

        $this->userData = $dataUser;
        $this->isSuperUser = (bool) $this->userData->isAdmin;

        return $this;
    }

    public function isAdmin()
    {
        return $this->isSuperUser;
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
        $controllerActionModules = $this->serviceLocator->get('AmModule\Service\ModuleService')->getControllerActionsModules();

        $dashToCamelFilter = new DashToCamelCase();

        $action = lcfirst($dashToCamelFilter->filter($action));

        if (!array_key_exists($controller . '.' . $action, $controllerActionModules)) {
            return false;
        }
        if ($this->isSuperUser) {
            return 1;
        }

        $permisos = json_decode($this->userData->permissions);

        $tienePermiso = in_array($controller.'.'.$action,$permisos);

        return (int) $tienePermiso;
    }

    public function redibujarMenu(&$dataMenu)
    {
        if ($this->isSuperUser) {
            return $dataMenu;
        }

        foreach ($dataMenu as $i => $menuTemp) {

            if ($menuTemp->zendName != '') {
                if (!$this->hasModuleAccess($menuTemp->zendName,$menuTemp->action)) {
                    //El usuario no tiene permisos de acceso. Eliminamos el registro del array
                    unset($dataMenu[$i]);
                    //no es necesario comprobar si hay acceso a los hijos porque directamente
                    //no hay acceso al padre, así que continuamos con la siguiente iteración.

                    continue;
                }
            }

            foreach($menuTemp->hijos as $indexHijo => $hijo) {
                if ($hijo->action != '') {
                    if (!$this->hasModuleAccess($hijo->zendName, $hijo->action)) {
                        //El usuario no tiene permisos de acceso. Eliminamos el registro del array
                        unset($menuTemp->hijos[$indexHijo]);
                    }
                }
            }
        }

        return $dataMenu;
    }
}