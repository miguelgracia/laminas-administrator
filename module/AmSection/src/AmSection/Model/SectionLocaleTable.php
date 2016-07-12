<?php

namespace AmSection\Model;

use Administrator\Model\AdministratorTable;

class SectionLocaleTable extends AdministratorTable
{
    protected $table = 'sections_locales';

    protected $entityModelName =  SectionLocaleModel::class;
}