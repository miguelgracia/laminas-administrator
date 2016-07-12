<?php

namespace AmMegabanner\Model;

use Administrator\Model\AdministratorTable;

class MegabannerTable extends AdministratorTable
{
    protected $table = 'megabanners';

    protected $entityModelName =  MegabannerModel::class;
}