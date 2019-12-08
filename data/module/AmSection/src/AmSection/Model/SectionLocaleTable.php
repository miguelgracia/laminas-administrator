<?php

namespace AmSection\Model;

use Administrator\Model\AdministratorTable;

class SectionLocaleTable extends AdministratorTable
{
    protected $table = 'app_routes_locales';

    public const ENTITY_MODEL_CLASS = SectionLocaleModel::class;
}
