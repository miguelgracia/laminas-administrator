<?php

namespace AmUser\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getDatatableConfig()
    {
        $controllerPlugin = $this->controllerPluginManager;

        $disallowSearchTo = array (
            'admin_users.id' => false,
        );

        $disallowOrderTo = $disallowSearchTo;

        $canEdit    = $this->permissions->hasModuleAccess('user', 'edit');
        $canDelete  = $this->permissions->hasModuleAccess('user', 'delete');

        return array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($canDelete, $canEdit) {
                //ocultamos la columna ID
                $header['admin_users.id']['options']['visible'] = false;

                //Renombramos las columnas para darles un nombre más descriptivo en el header de la tabla
                $header['admin_users.username']['value'] = 'Usuario';

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

                $link = "<a href='%s'><i class='col-xs-12 text-center fa %s'></i></a>";

                $controller = $controllerPlugin->getController();

                $editUrl = $controller->goToSection('user',array('action' => 'edit', 'id' => $row['id']),true);
                $deleteUrl = $controller->goToSection('user',array('action' => 'delete','id' => $row['id']),true);

                if ($canEdit) {
                    $row['edit'] = sprintf($link,$editUrl, 'fa-edit');
                } else {
                    $row['edit'] = '';
                }

                if ($canDelete) {
                    $row['delete'] = $row['id'] != "1"
                        ? sprintf($link,$deleteUrl, 'fa-remove js-eliminar-usuario')
                        : sprintf($link,"#_","fa-ban");
                } else {
                    $row['delete'] = '';
                }

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
                'username',
                'last_login',
            ),
            'from' => 'admin_users',
            'join' => array(
                array(
                    //Tabla con la que hacemos join
                    'admin_profiles',
                    //ON tal = tal
                    'admin_profile_id = admin_profiles.id',
                    array(
                        //Campos del join. La key es el alias del campo y el valor es el nombre del campo en sí
                        'name' => 'name'
                    ),
                    //Tipo de join
                    'left'
                ),
            ),
            //Los campos que estén dentro del 'having_fields' no se verán afectados por la clausula where al
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