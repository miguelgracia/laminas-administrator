<?php

namespace AmBlogCategory\Form;

use Administrator\Form\AdministratorFieldset;
use AmBlogCategory\Model\BlogCategoryLocaleTable;

class BlogCategoryLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = BlogCategoryLocaleTable::class;
}