<?php

namespace AmMegabanner\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $controllerPlugin = $this->controllerPluginManager;

        $disallowSearchTo = array (
            'megabanners.id' => false,
        );

        $disallowOrderTo = $disallowSearchTo;

        $canEdit    = $this->permissions->hasModuleAccess('megabanner', 'edit');
        $canDelete  = $this->permissions->hasModuleAccess('megabanner', 'delete');

        return array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($canDelete, $canEdit) {
                //ocultamos la columna ID
                $header['megabanners.id']['options']['visible'] = false;

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
                //Desde aquí podemos parsear los datos antes de visualizarlos por pantalla

                if ($row['is_video']) {
                    $row['element_url'] = sprintf("<a href='/Media/%s' target='_blank'>Ver video</a>", $row['element_url']);
                } else {
                    $row['element_url'] = sprintf("<img src='/Media/%s' width='100'/>", $row['element_url']);
                }


                $link = "<a href='%s'><i class='col-xs-12 text-center fa %s'></i></a>";

                $controller = $controllerPlugin->getController();

                $editUrl = $controller->goToSection('megabanner',array('action' => 'edit', 'id' => $row['id']),true);
                $deleteUrl = $controller->goToSection('megabanner',array('action' => 'delete','id' => $row['id']),true);

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
                'created_at',
                'active',
                'is_video'
            ),
            'from' => 'megabanners',
            'join' => array(
                array(
                    //Tabla con la que hacemos join
                    'megabanners_locales',
                    //ON tal = tal
                    'related_table_id = megabanners.id',
                    array(
                        //Campos del join. La key es el alias del campo y el valor es el nombre del campo en sí
                        'element_url' => 'element_url'
                    ),
                    //Tipo de join
                    'left'
                ),
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