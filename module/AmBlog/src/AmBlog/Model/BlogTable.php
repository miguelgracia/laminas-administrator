<?php

namespace AmBlog\Model;

use Administrator\Model\AdministratorTable;

class BlogTable extends AdministratorTable
{
    protected $table = 'blog_entries';

    protected $entityModelName = BlogModel::class;

}