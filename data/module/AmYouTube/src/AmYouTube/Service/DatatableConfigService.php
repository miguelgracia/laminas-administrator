<?php

namespace AmYouTube\Service;

use Administrator\Service\DatatableConfig;
use Administrator\Service\DatatableConfigInterface;

class DatatableConfigService extends DatatableConfig implements DatatableConfigInterface
{
    public function getViewParams()
    {
        $viewParams = parent::getViewParams();
        $controller = $this->controllerPluginManager->getController();
        $module = $this->controllerPluginManager->get('Params')->fromRoute('module');
        $viewParams['sync_action'] = $controller->goToSection($module, array('action' => 'sync'), true);
        return $viewParams;
    }

    public function getDatatableConfig()
    {
        $disallowSearchTo = array (
            'youtube_videos.id' => false,
        );

        $disallowOrderTo = $disallowSearchTo;

        $thisClass = $this;

        return array(
            'searchable' => $disallowSearchTo,
            'orderable' => $disallowOrderTo,
            'columns' => function ($header) use ($thisClass) {
                $header['youtube_videos.id']['options']['visible'] = false;
                $thisClass->setEditAndDeleteColumnsOptions($header);
                return $header;
            },
            'parse_row_data'=> function ($row) use($thisClass) {
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
                'title',
                'channel_title',
                'code',
                'visibility'
            ),
            'from' => 'youtube_videos',
            'join' => array(),

            //Los campos que están dentro del 'having_fields' no se verán afectados por la clausula where al
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