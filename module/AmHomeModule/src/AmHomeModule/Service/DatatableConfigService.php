<?php

namespace AmHomeModule\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $controllerPlugin = $this->controllerPluginManager;

        $disallowSearchTo = array (
            'home_modules.id' => false,
        );

        $disallowOrderTo = $disallowSearchTo;

        $canEdit    = $this->permissions->hasModuleAccess('home-module', 'edit');
        $canDelete  = $this->permissions->hasModuleAccess('home-module', 'delete');

        return array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($canDelete, $canEdit) {
                //ocultamos la columna ID
                $header['home_modules.id']['options']['visible'] = false;

                //Añadimos las columnas que contendrán los iconos de edición y activar/desactivar
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

                $editUrl = $controller->goToSection('home-module',array('action' => 'edit', 'id' => $row['id']),true);
                $deleteUrl = $controller->goToSection('home-module',array('action' => 'delete','id' => $row['id']),true);

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
                'key',
            ),
            'from' => 'home_modules',
            'join' => array(
            ),
            //Los campos que están dentro del 'having_fields' no se verán afectados por la clausula where al
            //filtar, sino por la clausula having. Esto es necesario para aquellos campos cuyo valor dependen
            //de una agrupación y deseamos filtrar por ellos.
            'having_fields' => array(

            ),
            /*'where' => array(

            ),*/
            'group' => array(

            )
        );
    }
}