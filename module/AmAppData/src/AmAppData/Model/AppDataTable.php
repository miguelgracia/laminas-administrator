<?php

namespace AmAppData\Model;

use Administrator\Model\AdministratorTable;

class AppDataTable extends AdministratorTable
{
    protected $table = 'app_datas';

    protected $entityModelName = AppDataModel::class;
}