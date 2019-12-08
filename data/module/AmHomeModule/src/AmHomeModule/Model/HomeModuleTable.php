<?php

namespace AmHomeModule\Model;

use Administrator\Model\AdministratorTable;

class HomeModuleTable extends AdministratorTable
{
    protected $table = 'home_modules';

    public const ENTITY_MODEL_CLASS = HomeModuleModel::class;
}
