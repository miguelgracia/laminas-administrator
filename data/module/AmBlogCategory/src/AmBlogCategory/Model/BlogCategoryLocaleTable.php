<?php

namespace AmBlogCategory\Model;

use Administrator\Model\AdministratorTable;

class BlogCategoryLocaleTable extends AdministratorTable
{
    protected $table = 'blog_categories_locales';

    public const ENTITY_MODEL_CLASS =  BlogCategoryLocaleModel::class;
}