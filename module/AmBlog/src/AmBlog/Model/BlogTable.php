<?php

namespace AmBlog\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;

class BlogTable extends AdministratorTable
{
    protected $table = 'blog_entries';

    protected $entityModelName = BlogModel::class;

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