<?php

namespace AmProfile\Form;

use Administrator\Form\AdministratorForm;

class ProfileForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                ProfileFieldset::class
            ]
        ];
    }
}
