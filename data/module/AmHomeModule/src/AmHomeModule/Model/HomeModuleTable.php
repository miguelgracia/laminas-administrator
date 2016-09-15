<?php

namespace AmHomeModule\Model;

use Administrator\Model\AdministratorTable;

class HomeModuleTable extends AdministratorTable
{
    protected $table = 'home_modules';

    protected $entityModelName =  HomeModuleModel::class;
}