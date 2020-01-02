<?php

namespace AmAccessoryCategory\Model;

use Administrator\Model\AdministratorTable;

class AccessoryCategoryLocaleTable extends AdministratorTable
{
    protected $table = 'accessory_categories_locales';

    public const ENTITY_MODEL_CLASS = AccessoryCategoryLocaleModel::class;
}
