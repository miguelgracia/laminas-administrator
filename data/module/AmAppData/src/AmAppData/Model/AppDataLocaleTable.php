<?php

namespace AmAppData\Model;

use Administrator\Model\AdministratorTable;

class AppDataLocaleTable extends AdministratorTable
{
    protected $table = 'app_datas_locales';

    protected $entityModelName = AppDataLocaleModel::class;
}