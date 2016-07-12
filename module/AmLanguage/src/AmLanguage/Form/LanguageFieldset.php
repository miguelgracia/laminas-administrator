<?php

namespace AmLanguage\Form;

use Administrator\Form\AdministratorFieldset;
use AmLanguage\Model\LanguageTable;

class LanguageFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = LanguageTable::class;

    public function initializers()
    {
        return array(
            'fieldValueOptions' => array(

                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
            )
        );
    }
}