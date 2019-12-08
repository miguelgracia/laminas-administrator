<?php

namespace AmBlogCategory\Form;

use Administrator\Form\AdministratorFieldset;
use AmBlogCategory\Model\BlogCategoryTable;

class BlogCategoryFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = BlogCategoryTable::class;
}
