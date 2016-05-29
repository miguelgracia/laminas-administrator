<?php

namespace AmBlogCategory\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $controllerPlugin = $this->controllerPluginManager;

        $disallowSearchTo = array (
            'blog_categories.id' => false,
        );

        $disallowOrderTo = $disallowSearchTo;

        $canEdit    = $this->permissions->hasModuleAccess('blog_category', 'edit');
        $canDelete  = $this->permissions->hasModuleAccess('blog_category', 'delete');

        return array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($canDelete, $canEdit) {
                //ocultamos la columna ID
                $header['blog_categories.id']['options']['visible'] = false;

                //A�adimos las columnas que contendr�n los iconos de edici�n y activar/desactivar
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

                $link = "<a href='%s'><i class='col-xs-12 text-center fa %s'></i></a>";

                $controller = $controllerPlugin->getController();

                $editUrl = $controller->goToSection('blog_category',array('action' => 'edit', 'id' => $row['id']),true);
                $deleteUrl = $controller->goToSection('blog_category',array('action' => 'delete','id' => $row['id']),true);

                $row['edit'] = $canEdit ? sprintf($link,$editUrl, 'fa-edit') : '';
                $row['delete'] = $canDelete ? sprintf($link, $deleteUrl, 'fa-remove js-eliminar') : '';

                return $row;
            }
        );
    }

    public function getQueryConfig()
    {
        return array(
            //En fields solo tenemos que a�adir los campos de la tabla indicada en 'from'
            'fields' => array(
                'id',
                'key',
            ),
            'from' => 'blog_categories',
            'join' => array(
                /*array(
                    //Tabla con la que hacemos join
                    'gestor_perfiles',
                    //ON tal = tal
                    'gestor_perfil_id = gestor_perfiles.id',
                    array(
                        //Campos del join. La key es el alias del campo y el valor es el nombre del campo en s�
                        'nombre' => 'nombre'
                    ),
                    //Tipo de join
                    'left'
                ),*/
            ),
            //Los campos que est�n dentro del 'having_fields' no se ver�n afectados por la clausula where al
            //filtar, sino por la clausula having. Esto es necesario para aquellos campos cuyo valor dependen
            //de una agrupaci�n y deseamos filtrar por ellos.
            'having_fields' => array(

            ),
            /*'where' => array(

            ),*/
            'group' => array(

            )
        );
    }
}