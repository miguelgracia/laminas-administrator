<?php

namespace AmProfile\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $disallowSearchTo = [
            'profile.id' => false,
        ];

        $disallowOrderTo = $disallowSearchTo;

        return [
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) {
                //ocultamos la columna ID
                $header['admin_profiles.id']['options']['visible'] = false;
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
                'name',
            ],
            'from' => 'admin_profiles',
            'join' => [
            ],

            'having_fields' => [
            ],
            'where' => [
            ],
            'group' => [
            ]
        ];
    }
}
