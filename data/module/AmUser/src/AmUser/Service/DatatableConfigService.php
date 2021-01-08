<?php

namespace AmUser\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $disallowSearchTo = [
            'admin_users.id' => false,
        ];

        $disallowOrderTo = $disallowSearchTo;

        return [
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) {
                //ocultamos la columna ID
                $header['admin_users.id']['options']['visible'] = false;
                $this->setEditAndDeleteColumnsOptions($header);
                return $header;
            },
            'parse_row_data' => function ($row) {
                //$row contiene los datos de cada una de las filas que ha generado la consulta.
                //Desde aqu� podemos parsear los datos antes de visualizarlos por pantalla

                $this->setEditAndDeleteColumnsValues($row);

                $row['active'] = $row['active'] == '1' ? 'SI' : 'NO';

                return $row;
            }
        ];
    }

    public function getQueryConfig()
    {
        return [
            //En fields solo tenemos que a�adir los campos de la tabla indicada en 'from'
            'fields' => [
                'id',
                'username',
                'last_login',
                'active'
            ],
            'from' => 'admin_users',
            'join' => [
                [
                    //Tabla con la que hacemos join
                    'admin_profiles',
                    //ON tal = tal
                    'admin_profile_id = admin_profiles.id',
                    [
                        //Campos del join. La key es el alias del campo y el valor es el nombre del campo en s�
                        'name' => 'name'
                    ],
                    //Tipo de join
                    'left'
                ],
            ],
            //Los campos que est�n dentro del 'having_fields' no se ver�n afectados por la clausula where al
            //filtar, sino por la clausula having. Esto es necesario para aquellos campos cuyo valor dependen
            //de una agrupaci�n y deseamos filtrar por ellos.
            'having_fields' => [
            ],
            /*'where' => array(

            ),*/
            'group' => [
            ]
        ];
    }
}
