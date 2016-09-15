<?php

namespace AmMegabanner\Model;

use Administrator\Model\AdministratorTable;

class MegabannerLocaleTable extends AdministratorTable
{
    protected $table = 'megabanners_locales';

    protected $entityModelName =  MegabannerLocaleModel::class;
}