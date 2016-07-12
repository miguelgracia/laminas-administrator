<?php

namespace AmStaticPage\Model;

use Administrator\Model\AdministratorTable;

class StaticPageTable extends AdministratorTable
{
    protected $table = 'static_pages';

    protected $entityModelName =  StaticPageModel::class;
}