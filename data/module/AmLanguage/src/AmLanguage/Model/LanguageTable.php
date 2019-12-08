<?php

namespace AmLanguage\Model;

use Administrator\Model\AdministratorTable;

class LanguageTable extends AdministratorTable
{
    protected $table = 'languages';

    public const ENTITY_MODEL_CLASS = LanguageModel::class;
}
