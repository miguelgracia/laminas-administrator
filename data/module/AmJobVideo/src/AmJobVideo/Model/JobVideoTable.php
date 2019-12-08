<?php

namespace AmJobVideo\Model;

use Administrator\Model\AdministratorTable;

class JobVideoTable extends AdministratorTable
{
    protected $table = 'jobs_videos';

    public const ENTITY_MODEL_CLASS = JobVideoModel::class;
}
