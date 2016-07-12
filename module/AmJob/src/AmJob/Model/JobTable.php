<?php

namespace AmJob\Model;

use Administrator\Model\AdministratorTable;

class JobTable extends AdministratorTable
{
    protected $table = 'jobs';

    protected $entityModelName =  JobModel::class;
}