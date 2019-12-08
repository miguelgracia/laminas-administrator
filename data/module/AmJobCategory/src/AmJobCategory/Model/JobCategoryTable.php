<?php

namespace AmJobCategory\Model;

use Administrator\Model\AdministratorTable;

class JobCategoryTable extends AdministratorTable
{
    protected $table = 'job_categories';

    public const ENTITY_MODEL_CLASS = JobCategoryModel::class;
}
