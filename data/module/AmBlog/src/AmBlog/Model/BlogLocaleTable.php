<?php

namespace AmBlog\Model;

use Administrator\Model\AdministratorTable;

class BlogLocaleTable extends AdministratorTable
{
    protected $table = 'blog_entries_locales';

    public const ENTITY_MODEL_CLASS = BlogLocaleModel::class;
}

