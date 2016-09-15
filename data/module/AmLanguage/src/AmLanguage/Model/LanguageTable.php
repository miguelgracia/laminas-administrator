<?php

namespace AmLanguage\Model;

use Administrator\Model\AdministratorTable;

class LanguageTable extends AdministratorTable
{
    protected $table = 'languages';

    protected $entityModelName =  LanguageModel::class;
}