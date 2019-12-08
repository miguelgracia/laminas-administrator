<?php

namespace AmUser\Form;

use Administrator\Form\AdministratorForm;

class AmUserForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                UserFieldset::class,
            ]
        ];
    }
}
