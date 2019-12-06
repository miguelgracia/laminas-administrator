<?php

namespace AmJobCategory\Model;

use Administrator\Model\AdministratorTable;

class JobCategoryLocaleTable extends AdministratorTable
{
    protected $table = 'job_categories_locales';

    public const ENTITY_MODEL_CLASS =  JobCategoryLocaleModel::class;
}