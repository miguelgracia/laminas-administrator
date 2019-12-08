<?php

namespace AmModule\Model;

use Administrator\Model\AdministratorTable;

class ModuleTable extends AdministratorTable
{
    protected $table = 'admin_modules';

    public const ENTITY_MODEL_CLASS = ModuleModel::class;
}
