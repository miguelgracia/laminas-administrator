<?php

namespace AmSection\Model;

use Administrator\Model\AdministratorTable;

class SectionLocaleTable extends AdministratorTable
{
    protected $table = 'app_routes_locales';

    protected $entityModelName =  SectionLocaleModel::class;
}