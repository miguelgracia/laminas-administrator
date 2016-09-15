<?php

namespace AmJob\Model;

use Administrator\Model\AdministratorTable;

class JobLocaleTable extends AdministratorTable
{
    protected $table = 'jobs_locales';

    protected $entityModelName =  JobLocaleModel::class;
}