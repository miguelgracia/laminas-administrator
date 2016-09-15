<?php

namespace AmSection\Model;

use Administrator\Model\AdministratorTable;

class SectionTable extends AdministratorTable
{
    protected $table = 'app_routes';

    protected $entityModelName =  SectionModel::class;
}