<?php

namespace AmSection\Model;

use Administrator\Model\AdministratorTable;

class SectionTable extends AdministratorTable
{
    protected $table = 'app_routes';

    public const ENTITY_MODEL_CLASS =  SectionModel::class;
}