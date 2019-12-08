<?php

namespace AmAppData\Form;

use Administrator\Form\AdministratorForm;

class AppDataForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                AppDataFieldset::class,
                AppDataLocaleFieldset::class,
            ]
        ];
    }
}
