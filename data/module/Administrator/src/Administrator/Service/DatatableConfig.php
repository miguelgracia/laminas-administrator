<?php

namespace Administrator\Service;

use Zend\Filter\Word\DashToCamelCase;
use Zend\Mvc\Controller\Plugin\Params;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class DatatableConfig
{
    protected $serviceLocator;
    protected $controllerPluginManager;
    protected $permissions;

    /**
     * @var Params
     */
    protected $params;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $this->controllerPluginManager = $this->serviceLocator->get('ControllerPluginManager');

        $this->permissions = $this->serviceLocator->get('AmProfile\Service\ProfilePermissionService');

        $this->params = $this->controllerPluginManager->get('Params');
    }

    public function getViewParams()
    {
        $translator = $this->serviceLocator->get('Translator');

        $module = $this->params->fromRoute('module');

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

    protected function setEditAndDeleteColumnsOptions(&$header)
    {
        $module = $this->params->fromRoute('module');

        $canEdit = $this->permissions->hasModuleAccess($module, 'edit');
        $canDelete = $this->permissions->hasModuleAccess($module, 'delete');

        if($canEdit) {
            //Añadimos las columnas que contendrán los iconos de edición y activar/desactivar
            $header['edit'] = array(
                'value' => 'Modificar',
                'options' => array(
                    'orderable' => false,
                    'searchable' => false,
                    'visible' => $canEdit
                )
            );
        }

        if ($canDelete) {
            $header['delete'] = array(
                'value' => 'Eliminar',
                'options' => array(
                    'orderable' => false,
                    'searchable' => false,
                    'visible' => $canDelete
                )
            );
        }
    }

    public function setEditAndDeleteColumnsValues(&$row)
    {
        $module = $this->params->fromRoute('module');

        $canEdit = $this->permissions->hasModuleAccess($module, 'edit');
        $canDelete = $this->permissions->hasModuleAccess($module, 'delete');

        $link = "<a href='%s'><i class='col-xs-12 text-center fa %s'></i></a>";

        $controller = $this->controllerPluginManager->getController();

        $module = $this->params->fromRoute('module');

        $editUrl = $controller->goToSection($module,array('action' => 'edit', 'id' => $row['id']),true);
        $deleteUrl = $controller->goToSection($module,array('action' => 'delete','id' => $row['id']),true);

        if ($canEdit) {
            $row['edit'] = sprintf($link,$editUrl, 'fa-edit');
        }

        if ($canDelete) {
            $row['delete'] = sprintf($link, $deleteUrl, 'fa-remove js-eliminar');
        }
    }
}