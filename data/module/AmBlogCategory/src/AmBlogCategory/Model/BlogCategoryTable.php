<?php

namespace AmBlogCategory\Model;

use Administrator\Model\AdministratorTable;

class BlogCategoryTable extends AdministratorTable
{
    protected $table = 'blog_categories';

    public const ENTITY_MODEL_CLASS =  BlogCategoryModel::class;
}