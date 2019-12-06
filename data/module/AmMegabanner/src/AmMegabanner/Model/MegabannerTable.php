<?php

namespace AmMegabanner\Model;

use Administrator\Model\AdministratorTable;

class MegabannerTable extends AdministratorTable
{
    protected $table = 'megabanners';

    public const ENTITY_MODEL_CLASS =  MegabannerModel::class;
}