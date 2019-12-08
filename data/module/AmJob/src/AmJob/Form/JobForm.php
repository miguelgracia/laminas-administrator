<?php

namespace AmJob\Form;

use Administrator\Form\AdministratorForm;

class JobForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                JobFieldset::class,
                JobLocaleFieldset::class,
            ]
        ];
    }
}
