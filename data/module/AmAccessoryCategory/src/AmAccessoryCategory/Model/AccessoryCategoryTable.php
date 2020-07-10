<?php

namespace AmAccessoryCategory\Model;

use Administrator\Model\AdministratorTable;

class AccessoryCategoryTable extends AdministratorTable
{
    protected $table = 'accessory_categories';

    public const ENTITY_MODEL_CLASS = AccessoryCategoryModel::class;
}
