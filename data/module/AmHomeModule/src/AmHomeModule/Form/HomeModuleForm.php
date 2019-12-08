<?php

namespace AmHomeModule\Form;

use Administrator\Form\AdministratorForm;

class HomeModuleForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                HomeModuleFieldset::class,
                HomeModuleLocaleFieldset::class,
            ]
        ];
    }
}
