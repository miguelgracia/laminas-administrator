<?php

namespace AmAccessory\Model;

use Administrator\Model\AdministratorTable;

class AccessoryTable extends AdministratorTable
{
    protected $table = 'accessories';

    public const ENTITY_MODEL_CLASS = AccessoryModel::class;

    public function save($model, $id = 0, $fieldKey = 'id')
    {
        if (is_array($model->imageUrl)) {
            $model->imageUrl = $model->getImageUrl();
        }

        return parent::save($model, $id, $fieldKey);
    }
}
