<?php

namespace AmBlogCategory\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $disallowSearchTo = array (
            'blog_categories.id' => false,
        );

        $disallowOrderTo = $disallowSearchTo;

        $thisClass = $this;

        return array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($thisClass) {
                //ocultamos la columna ID
                $header['blog_categories.id']['options']['visible'] = false;
                $thisClass->setEditAndDeleteColumnsOptions($header);
                return $header;
            },
            'parse_row_data'=> function ($row) use($thisClass) {

                //$row contiene los datos de cada una de las filas que ha generado la consulta.
                //Desde aquí podemos parsear los datos antes de visualizarlos por pantalla
                $thisClass->setEditAndDeleteColumnsValues($row);
                $row['active'] = $row['active'] == '1' ? 'SI' : 'NO';
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
                'active',
            ),
            'from' => 'blog_categories',
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

            )
        );
    }
}