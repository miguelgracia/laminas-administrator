<?php

namespace AmBlog\Model;

use Administrator\Model\AdministratorTable;

class BlogLocaleTable extends AdministratorTable
{
    protected $table = 'blog_entries_locales';

    protected $entityModelName = BlogLocaleModel::class;
}

