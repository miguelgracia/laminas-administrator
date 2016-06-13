<?php

namespace AmMedia\Model;

use Administrator\Model\AdministratorTable;

class MediaTable extends AdministratorTable
{
    protected $table = 'media';

    public function findByModel($model,$id_parent)
    {
        return $this->select(array(
            'model' => $model,
            'id_parent' => $id_parent
        ));
    }
}