<?php

namespace AmModule\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $disallowSearchTo = array (
            'admin_modules.id' => false,
        );

        $disallowOrderTo = $disallowSearchTo;

        $thisClass = $this;

        return array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($thisClass) {
                //ocultamos la columna ID
                $header['admin_modules.id']['options']['visible'] = false;

                $thisClass->setEditAndDeleteColumnsOptions($header);
                return $header;
            },
            'parse_row_data'=> function ($row) use($thisClass) {

                //$row contiene los datos de cada una de las filas que ha generado la consulta.
                $thisClass->setEditAndDeleteColumnsValues($row);
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