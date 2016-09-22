<?php

namespace AmHomeModule\Model;

use Administrator\Model\AdministratorTable;

class HomeModuleLocaleTable extends AdministratorTable
{
    protected $table = 'home_modules_locales';

    protected $entityModelName =  HomeModuleLocaleModel::class;

    public function save($model, $id = 0, $fieldKey = 'id')
    {
        if (is_array($model->imageUrl)) {
            $model->imageUrl = json_encode($model->imageUrl);
        }

        return parent::save($model, $id, $fieldKey);
    }
}