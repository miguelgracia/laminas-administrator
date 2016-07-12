<?php

namespace AmModule\Model;

use Administrator\Model\AdministratorTable;

class ModuleTable extends AdministratorTable
{
    protected $table = "admin_modules";

    protected $entityModelName =  ModuleModel::class;
}