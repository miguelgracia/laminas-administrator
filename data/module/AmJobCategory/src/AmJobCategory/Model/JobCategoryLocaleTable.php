<?php

namespace AmJobCategory\Model;

use Administrator\Model\AdministratorTable;

class JobCategoryLocaleTable extends AdministratorTable
{
    protected $table = 'job_categories_locales';

    protected $entityModelName =  JobCategoryLocaleModel::class;
}