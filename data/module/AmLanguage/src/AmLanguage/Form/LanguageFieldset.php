<?php

namespace AmLanguage\Form;

use Administrator\Form\AdministratorFieldset;
use AmLanguage\Model\LanguageTable;

class LanguageFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = LanguageTable::class;
}
