<?php

namespace AmSection\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $disallowSearchTo = [
            'app_routes.id' => false,
        ];

        $disallowOrderTo = $disallowSearchTo;

        $thisClass = $this;

        return [
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($thisClass) {
                //ocultamos la columna ID
                $header['app_routes.id']['options']['visible'] = false;

                $thisClass->setEditAndDeleteColumnsOptions($header);

                return $header;
            },
            'parse_row_data' => function ($row) use ($thisClass) {
                //$row contiene los datos de cada una de las filas que ha generado la consulta.
                $thisClass->setEditAndDeleteColumnsValues($row);

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
            'from' => 'app_routes',
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
