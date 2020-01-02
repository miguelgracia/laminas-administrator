<?php

namespace AmAccessory\Form;

use Administrator\Form\AdministratorForm;

class AccessoryForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                AccessoryFieldset::class,
                AccessoryLocaleFieldset::class,
            ]
        ];
    }
}
