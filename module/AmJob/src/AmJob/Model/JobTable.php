<?php

namespace AmJob\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;

class JobTable extends AdministratorTable
{
    protected $table = 'jobs';

    protected $entityModelName =  JobModel::class;

    public function find($id, $key = 'id')
    {
        $row = parent::find($id, $key);
        $row->imageUrl = $row->getImageUrl();
        return $row;
    }

    public function save(AdministratorModel $model)
    {
        if (is_array($model->imageUrl)) {
            $model->imageUrl = json_encode($model->imageUrl);
        }

        return parent::save($model);
    }
}