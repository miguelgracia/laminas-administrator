<?php

namespace AmModule\Form;

use Administrator\Form\AdministratorForm;

class ModuleForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                ModuleFieldset::class
            ]
        ];
    }
}
