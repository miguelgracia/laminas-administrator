<?php

namespace AmCustomer\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $disallowSearchTo = [
            'customers.id' => false,
        ];

        $disallowOrderTo = $disallowSearchTo;

        return [
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) {
                //ocultamos la columna ID
                $header['customers.id']['options']['visible'] = false;

                $this->setEditAndDeleteColumnsOptions($header);

                return $header;
            },
            'parse_row_data' => function ($row) {
                //$row contiene los datos de cada una de las filas que ha generado la consulta.
                $this->setEditAndDeleteColumnsValues($row);

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
                'name',
            ],
            'from' => 'customers',
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
