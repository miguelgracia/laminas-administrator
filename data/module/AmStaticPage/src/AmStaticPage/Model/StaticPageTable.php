<?php

namespace AmStaticPage\Model;

use Administrator\Model\AdministratorTable;

class StaticPageTable extends AdministratorTable
{
    protected $table = 'static_pages';

    public const ENTITY_MODEL_CLASS = StaticPageModel::class;
}
