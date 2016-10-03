<?php

namespace AmMegabanner\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $disallowSearchTo = array (
            'megabanners.id' => false,
        );

        $disallowOrderTo = $disallowSearchTo;

        $thisClass = $this;

        return array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($thisClass) {
                //ocultamos la columna ID
                $header['megabanners.id']['options']['visible'] = false;
                $thisClass->setEditAndDeleteColumnsOptions($header);
                return $header;
            },
            'parse_row_data'=> function ($row) use($thisClass) {

                //$row contiene los datos de cada una de las filas que ha generado la consulta.
                //Desde aquí podemos parsear los datos antes de visualizarlos por pantalla

                if ($row['is_video']) {
                    $row['element_url'] = sprintf("<a href='%s' target='_blank'>Ver video</a>", $row['element_url']);
                } else {
                    $row['element_url'] = sprintf("<img src='%s' width='100'/>", $row['element_url']);
                }

                $thisClass->setEditAndDeleteColumnsValues($row);

                $row['active'] = $row['active'] == '1' ? 'SI' : 'NO';
                $row['is_video'] = $row['is_video'] == '1' ? 'SI' : 'NO';

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
                'active',
                'is_video',
                'element_url',
                'order'
            ),
            'from' => 'megabanners',
            'join' => array(

            ),
            //Los campos que están dentro del 'having_fields' no se veran afectados por la clausula where al
            //filtar, sino por la clausula having. Esto es necesario para aquellos campos cuyo valor dependen
            //de una agrupación y deseamos filtrar por ellos.
            'having_fields' => array(

            ),
            /*'where' => array(

            ),*/
            'group' => array(
                'megabanners.id',
            )
        );
    }
}