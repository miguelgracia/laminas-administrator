<?php

namespace AmAppData\Model;

use Administrator\Model\AdministratorTable;

class AppDataLocaleTable extends AdministratorTable
{
    protected $table = 'app_datas_locales';

    public const ENTITY_MODEL_CLASS = AppDataLocaleModel::class;
}