<?php

namespace AmModule\Service;


use Zend\Code\Reflection\ClassReflection;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\DashToCamelCase;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleService implements FactoryInterface
{
    protected $modules = array();
    protected $controllerActions;
    protected $sm;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->sm = $serviceLocator;

        $config = $serviceLocator->get('ApplicationConfig');

        $modules = $config['modules'];

        //Devolvemos sólo aquellos módulos con el prefijo "Am" y que no estén en el array de hidden_modules
        $this->modules = array_filter($modules, function ($value) use($config) {
            return preg_match("/^Am/",$value) === 1 and !in_array($value, $config['hidden_modules']);
        });

        $this->updateAvailableModules();

        return $this;
    }

    public function updateAvailableModules()
    {
        $moduleTable = $this->sm->get('AmModule\Model\ModuleTable');

        $availableModules = $moduleTable->select()->toKeyValueArray('id','zendName');

        $newModules = array();

        $filter = new CamelCaseToDash();

        foreach ($this->modules as $module) {

            $module = mb_strtolower($filter->filter(preg_replace('/^Am/i','', $module)));

            if (!in_array($module, $availableModules)) {

                $newModules[] = $module;

                $moduleTable->save(array(
                    'zend_name' => $module,
                    'public_name' => $module
                ));
            }
        }

        return $newModules;
    }

    public function getModules()
    {
        return $this->modules;
    }

    public function getControllerActionsModules()
    {
        if ($this->controllerActions) {
            return $this->controllerActions;
        }

        $listaControladores = $this->sm->get('AmModule\Model\ModuleTable')->select();

        $filterDashToCamelCase = new DashToCamelCase();

        $hiddenMethods = array(
            'getMethodFromAction',
            'notFoundAction'
        );

        $controllerActions = array();

        foreach ($listaControladores as $i => $controller) {

            $controllerNamespace = '\Am%s\Controller\Am%sModuleController';

            $controllerName = $filterDashToCamelCase->filter($controller->zendName);

            $class = sprintf($controllerNamespace,$controllerName,$controllerName);

            if (class_exists($class)) {
                $reflectionController = new ClassReflection($class);
                $controllerMethods = $reflectionController->getMethods();

                $actions = array();
                foreach ($controllerMethods as $method) {

                    $name = $method->getName();

                    if (stripos($name, 'action') !== false and !in_array($name,$hiddenMethods)) {
                        $action = preg_replace("/(Action)$/", "$2", $name);
                        $controllerActions[$controller->zendName . '.' .$action] = $action;
                    }
                }
                asort($actions);

            }
        }

        $this->controllerActions = $controllerActions;

        return $controllerActions;
    }
}