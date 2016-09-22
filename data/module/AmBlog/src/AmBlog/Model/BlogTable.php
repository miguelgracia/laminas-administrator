<?php

namespace AmBlog\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;

class BlogTable extends AdministratorTable
{
    protected $table = 'blog_entries';

    protected $entityModelName = BlogModel::class;

    public function save($model, $id = 0, $fieldKey = 'id')
    {
        if (is_array($model->imageUrl)) {
            $model->imageUrl = $model->getImageUrl();
        }

        return parent::save($model, $id, $fieldKey);
    }
}