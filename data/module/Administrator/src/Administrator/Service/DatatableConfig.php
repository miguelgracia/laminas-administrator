<?php

namespace Administrator\Service;

use Zend\Filter\Word\DashToCamelCase;
use Zend\Mvc\Controller\Plugin\Params;

abstract class DatatableConfig
{
    protected $controllerPluginManager;
    protected $permissions;

    protected $translator;

    public function __construct(
        $controllerPluginManager,
        $permissions,
        $translator
    ) {
        $this->controllerPluginManager = $controllerPluginManager;
        $this->permissions = $permissions;
        $this->translator = $translator;
    }

    public function getViewParams()
    {
        $module = $this->controllerPluginManager->get('Params')->fromRoute('module');

        $filter = new DashToCamelCase();

        $viewParams = [
            'table_id' => lcfirst($filter->filter($module)) . 'Table',
            'title' => sprintf($this->translator->translate('List of %s module'), $module)
        ];

        $addAction = 'add';

        if ($this->permissions->hasModuleAccess($module, $addAction)) {
            $controller = $this->controllerPluginManager->getController();

            if (method_exists($controller, $addAction . 'Action')) {
                $viewParams['add_action'] = $controller->goToSection($module, ['action' => $addAction], true);
            }
        }
        return $viewParams;
    }

    protected function setEditAndDeleteColumnsOptions(&$header)
    {
        $module = $this->controllerPluginManager->get('Params')->fromRoute('module');

        $canEdit = $this->permissions->hasModuleAccess($module, 'edit');
        $canDelete = $this->permissions->hasModuleAccess($module, 'delete');

        if ($canEdit) {
            //Añadimos las columnas que contendrán los iconos de edición y activar/desactivar
            $header['edit'] = [
                'value' => $this->translator->translate('Edit'),
                'options' => [
                    'orderable' => false,
                    'searchable' => false,
                    'visible' => $canEdit
                ]
            ];
        }

        if ($canDelete) {
            $header['delete'] = [
                'value' => $this->translator->translate('Delete'),
                'options' => [
                    'orderable' => false,
                    'searchable' => false,
                    'visible' => $canDelete
                ]
            ];
        }
    }

    public function setEditAndDeleteColumnsValues(&$row)
    {
        $params = $this->controllerPluginManager->get('Params');
        $module = $params->fromRoute('module');

        $canEdit = $this->permissions->hasModuleAccess($module, 'edit');
        $canDelete = $this->permissions->hasModuleAccess($module, 'delete');

        $link = "<a href='%s'><i class='col-xs-12 text-center fa %s'></i></a>";

        $controller = $this->controllerPluginManager->getController();

        $module = $params->fromRoute('module');

        $editUrl = $controller->goToSection($module, ['action' => 'edit', 'id' => $row['id']], true);
        $deleteUrl = $controller->goToSection($module, ['action' => 'delete', 'id' => $row['id']], true);

        if ($canEdit) {
            $row['edit'] = sprintf($link, $editUrl, 'fa-edit');
        }

        if ($canDelete) {
            $row['delete'] = sprintf($link, $deleteUrl, 'fa-remove js-eliminar');
        }
    }
}
