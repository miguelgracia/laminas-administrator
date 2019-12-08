<?php

namespace AmJob\Model;

use Administrator\Model\AdministratorTable;

class JobTable extends AdministratorTable
{
    protected $table = 'jobs';

    public const ENTITY_MODEL_CLASS = JobModel::class;

    public function save($model, $id = 0, $fieldKey = 'id')
    {
        if (is_array($model->imageUrl)) {
            $model->imageUrl = $model->getImageUrl();
        }

        return parent::save($model, $id, $fieldKey);
    }
}
