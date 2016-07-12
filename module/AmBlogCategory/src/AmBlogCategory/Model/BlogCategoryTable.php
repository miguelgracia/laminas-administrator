<?php

namespace AmBlogCategory\Model;

use Administrator\Model\AdministratorTable;

class BlogCategoryTable extends AdministratorTable
{
    protected $table = 'blog_categories';

    protected $entityModelName =  BlogCategoryModel::class;
}