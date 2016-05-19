<?php

namespace AmModule\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleService implements FactoryInterface
{
    protected $modules = array();
    protected $sm;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->sm = $serviceLocator;

        $config = $serviceLocator->get('ApplicationConfig');

        $modules = $config['modules'];

        $this->modules = array_filter($modules, function ($value) use($config) {
            return preg_match("/^Am/",$value) === 1 and !in_array($value, $config['hidden_modules']);
        });

        $this->updateAvailableModules();

        return $this;
    }

    public function updateAvailableModules()
    {
        $moduleTable = $this->sm->get('AmModule\Model\ModuleTable');

        $availableModules = $moduleTable->select()->toKeyValueArray('id','nombreZend');

        $newModules = array();

        foreach ($this->modules as $module) {

            $module = mb_strtolower(preg_replace('/^Am/','',$module));

            if (!in_array($module, $availableModules)) {
                $newModules[] = $module;

                $moduleTable->save(array(
                    'nombre_zend' => $module,
                    'nombre_usable' => $module
                ));
            }
        }

        return $newModules;
    }
}