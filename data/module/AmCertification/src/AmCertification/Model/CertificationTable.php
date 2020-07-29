<?php

namespace AmCertification\Model;

use Administrator\Model\AdministratorTable;

class CertificationTable extends AdministratorTable
{
    protected $table = 'certifications';

    public const ENTITY_MODEL_CLASS = CertificationModel::class;

    public function save($model, $id = 0, $fieldKey = 'id')
    {
        if (is_array($model->imageUrl)) {
            $model->imageUrl = $model->getImageUrl();
        }

        return parent::save($model, $id, $fieldKey);
    }
}
