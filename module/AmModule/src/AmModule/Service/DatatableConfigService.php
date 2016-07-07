<?php

namespace AmModule\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $controllerPlugin = $this->controllerPluginManager;

        $disallowSearchTo = array (
            'admin_modules.id' => false,
        );

        $disallowOrderTo = $disallowSearchTo;

        $canEdit    = $this->permissions->hasModuleAccess('module', 'edit');
        $canDelete  = $this->permissions->hasModuleAccess('module', 'delete');

        return array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($canDelete, $canEdit) {
                //ocultamos la columna ID
                $header['admin_modules.id']['options']['visible'] = false;

                $header['edit'] = array(
                    'value' => 'Modificar',
                    'options' => array(
                        'orderable' => false,
                        'searchable' => false,
                        'visible' => $canEdit
                    )
                );

                $header['delete'] = array(
                    'value' => 'Eliminar',
                    'options' => array(
                        'orderable' => false,
                        'searchable' => false,
                        'visible' => $canDelete
                    )
                );

                return $header;
            },
            'parse_row_data'=> function ($row) use($controllerPlugin, $canDelete, $canEdit) {

                //$row contiene los datos de cada una de las filas que ha generado la consulta.

                $link = "<a href='%s'><i class='col-xs-12 text-center fa %s'></i></a>";

                $controller = $controllerPlugin->getController();

                $editUrl = $controller->goToSection('module',array('action' => 'edit', 'id' => $row['id']),true);
                $deleteUrl = $controller->goToSection('module',array('action' => 'delete','id' => $row['id']),true);

                $row['edit'] = $canEdit ? sprintf($link,$editUrl, 'fa-edit') : '';
                $row['delete'] = $canDelete ? sprintf($link, $deleteUrl, 'fa-remove js-eliminar') : '';

                return $row;
            }
        );
    }

    public function getQueryConfig()
    {
        return array(
            //En fields solo tenemos que añadir los campos de la tabla indicada en 'from'
            'fields' => array(
                'id',
                'zend_name',
                'public_name'
            ),
            'from' => 'admin_modules',
            'join' => array(

            ),

            'having_fields' => array(

            ),
            'where' => array(

            ),
            'group' => array(

            )
        );
    }
}