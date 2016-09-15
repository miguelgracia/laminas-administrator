<?php

namespace AmHomeModule\Model;

use Administrator\Model\AdministratorTable;

class HomeModuleLocaleTable extends AdministratorTable
{
    protected $table = 'home_modules_locales';

    protected $entityModelName =  HomeModuleLocaleModel::class;
}