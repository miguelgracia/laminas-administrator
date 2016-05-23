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
            'gestor_usuarios.id' => false,
        );

        $disallowOrderTo = $disallowSearchTo;

        return array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) {
                //ocultamos la columna ID
                $header['gestor_usuarios.id']['options']['visible']            = false;

                //Renombramos las columnas para darles un nombre más descriptivo en el header de la tabla
                $header['gestor_usuarios.login']['value']                     = 'Usuario';

                //Añadimos las columnas que contendrán los iconos de edición y activar/desactivar
                $header['edit'] = array(
                    'value' => 'Modificar',
                    'options' => array(
                        'orderable' => false,
                        'searchable' => false,
                    )
                );

                $header['delete'] = array(
                    'value' => 'Eliminar',
                    'options' => array(
                        'orderable' => false,
                        'searchable' => false,
                    )
                );

                return $header;
            },
            'parse_row_data'=> function ($row) use($controllerPlugin) {

                //$row contiene los datos de cada una de las filas que ha generado la consulta.
                //Desde aquí podemos parsear los datos antes de visualizarlos por pantalla

                $link = "<a href='%s'><i class='col-xs-12 text-center fa %s'></i></a>";

                $controller = $controllerPlugin->getController();

                $editUrl = $controller->goToSection('user',array('action' => 'edit', 'id' => $row['id']),true);
                $deleteUrl = $controller->goToSection('user',array('action' => 'delete','id' => $row['id']),true);

                $row['edit'] = sprintf($link,$editUrl, 'fa-edit');

                $misPermisos = $this->serviceLocator->get('AmProfile\Service\ProfilePermissionService');

                if ($misPermisos->hasModuleAccess('user', 'delete')) {
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
                'login',
                'last_login',
            ),
            'from' => 'gestor_usuarios',
            'join' => array(
                array(
                    //Tabla con la que hacemos join
                    'gestor_perfiles',
                    //ON tal = tal
                    'gestor_perfil_id = gestor_perfiles.id',
                    array(
                        //Campos del join. La key es el alias del campo y el valor es el nombre del campo en sí
                        'nombre' => 'nombre'
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

    public function getViewParams()
    {
        return array(
            'table_id'   => 'userTable',
            'add_action' => $this->controllerPluginManager->getController()->goToSection('user',array('action'=>'add'),true),
            'title'      => 'Listado de usuarios'
        );
    }
}