<?php

namespace AmStaticPage\Model;

use Administrator\Model\AdministratorTable;

class StaticPageLocaleTable extends AdministratorTable
{
    protected $table = 'static_pages_locales';

    public const ENTITY_MODEL_CLASS = StaticPageLocaleModel::class;
}
