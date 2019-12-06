<?php

namespace AmAppData\Model;

use Administrator\Model\AdministratorTable;

class AppDataTable extends AdministratorTable
{
    protected $table = 'app_datas';

    public const ENTITY_MODEL_CLASS = AppDataModel::class;
}