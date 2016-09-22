<?php

namespace AmBlog\Model;

use Administrator\Model\AdministratorModel;
use Administrator\Model\AdministratorTable;

class BlogTable extends AdministratorTable
{
    protected $table = 'blog_entries';

    protected $entityModelName = BlogModel::class;

    public function save($data, $id = 0, $fieldKey = 'id')
    {
        if (is_array($data->imageUrl)) {
            $data->imageUrl = json_encode($data->imageUrl);
        }

        return parent::save($data, $id, $fieldKey);
    }
}