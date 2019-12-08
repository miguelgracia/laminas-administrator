<?php

namespace AmJob\Model;

use Administrator\Model\AdministratorTable;

class JobLocaleTable extends AdministratorTable
{
    protected $table = 'jobs_locales';

    public const ENTITY_MODEL_CLASS = JobLocaleModel::class;
}
