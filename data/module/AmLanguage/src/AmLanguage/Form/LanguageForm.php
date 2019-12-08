<?php

namespace AmLanguage\Form;

use Administrator\Form\AdministratorForm;

class LanguageForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                LanguageFieldset::class
            ]
        ];
    }
}
