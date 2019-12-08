<?php

namespace AmPartner\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $disallowSearchTo = [
            'partners.id' => false,
        ];

        $disallowOrderTo = $disallowSearchTo;

        $thisClass = $this;

        return [
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($thisClass) {
                //ocultamos la columna ID
                $header['partners.id']['options']['visible'] = false;

                $thisClass->setEditAndDeleteColumnsOptions($header);

                return $header;
            },
            'parse_row_data' => function ($row) use ($thisClass) {
                //$row contiene los datos de cada una de las filas que ha generado la consulta.
                //Desde aquí podemos parsear los datos antes de visualizarlos por pantalla
                $thisClass->setEditAndDeleteColumnsValues($row);

                $row['active'] = $row['active'] == '1' ? 'SI' : 'NO';

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
                'website',
                'active',
            ],
            'from' => 'partners',
            'join' => [],

            'having_fields' => [
            ],
            /*'where' => array(

            ),*/
            'group' => []
        ];
    }
}
