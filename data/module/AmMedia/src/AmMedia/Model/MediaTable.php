<?php

namespace AmMedia\Model;

use Administrator\Model\AdministratorTable;

class MediaTable extends AdministratorTable
{
    protected $table = 'media';

    public const ENTITY_MODEL_CLASS = MediaModel::class;

    public function findByModel($model, $id_parent)
    {
        return $this->select([
            'model' => $model,
            'id_parent' => $id_parent
        ]);
    }
}
