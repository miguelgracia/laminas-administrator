<?php

namespace AmAppData\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $controllerPlugin = $this->controllerPluginManager;

        $disallowSearchTo = [
            'app_datas.id' => false,
        ];

        $disallowOrderTo = $disallowSearchTo;

        $canEdit = $this->permissions->hasModuleAccess('app-data', 'edit');
        $canDelete = $this->permissions->hasModuleAccess('app-data', 'delete');

        return [
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($canDelete, $canEdit) {
                //ocultamos la columna ID
                $header['app_datas.id']['options']['visible'] = false;

                //Añadimos las columnas que contendrán los iconos de edición y activar/desactivar
                $header['edit'] = [
                    'value' => 'Modificar',
                    'options' => [
                        'orderable' => false,
                        'searchable' => false,
                        'visible' => $canEdit
                    ]
                ];

                $header['delete'] = [
                    'value' => 'Eliminar',
                    'options' => [
                        'orderable' => false,
                        'searchable' => false,
                        'visible' => $canDelete
                    ]
                ];

                return $header;
            },
            'parse_row_data' => function ($row) use ($controllerPlugin, $canDelete, $canEdit) {
                //$row contiene los datos de cada una de las filas que ha generado la consulta.

                $link = "<a href='%s'><i class='col-xs-12 text-center fa %s'></i></a>";

                $controller = $controllerPlugin->getController();

                $editUrl = $controller->getUrlSection('app-data', ['action' => 'edit', 'id' => $row['id']]);
                $deleteUrl = $controller->getUrlSection('app-data', ['action' => 'delete', 'id' => $row['id']]);

                $row['edit'] = $canEdit ? sprintf($link, $editUrl, 'fa-edit') : '';
                $row['delete'] = $canDelete ? sprintf($link, $deleteUrl, 'fa-remove js-eliminar') : '';

                return $row;
            }
        ];
    }

    public function getQueryConfig()
    {
        return [
            //En fields solo tenemos que añadir los campos de la tabla indicada en 'from'
            'fields' => [
                'id',
                'key',
            ],
            'from' => 'app_datas',
            'join' => [
            ],
            //Los campos que están dentro del 'having_fields' no se verán afectados por la clausula where al
            //filtar, sino por la clausula having. Esto es necesario para aquellos campos cuyo valor dependen
            //de una agrupación y deseamos filtrar por ellos.
            'having_fields' => [
            ],
            /*'where' => array(

            ),*/
            'group' => [
            ]
        ];
    }
}
