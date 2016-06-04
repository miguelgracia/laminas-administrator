<?php

namespace Administrator\Service;

use Zend\Filter\Word\DashToCamelCase;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class DatatableConfig
{
    protected $serviceLocator;
    protected $controllerPluginManager;
    protected $permissions;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $this->controllerPluginManager = $this->serviceLocator->get('ControllerPluginManager');

        $this->permissions = $this->serviceLocator->get('AmProfile\Service\ProfilePermissionService');
    }

    public function getViewParams()
    {
        $params = $this->controllerPluginManager->get('Params');
        $translator = $this->serviceLocator->get('Translator');

        $module = $params->fromRoute('module');

        $filter = new DashToCamelCase();

        $viewParams = array(
            'table_id'   => lcfirst($filter->filter($module)).'Table',
            'title'      => sprintf($translator->translate("List of %s module"),$module)
        );

        $addAction = 'add';

        if ($this->permissions->hasModuleAccess($module, $addAction)) {
            $controller = $this->controllerPluginManager->getController();

            if (method_exists($controller, $addAction .'Action')) {
                $viewParams['add_action'] = $controller->goToSection($module, array('action' => $addAction), true);
            }
        }
        return $viewParams;
    }
}