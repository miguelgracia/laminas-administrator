<?php

namespace AmMegabanner\Form;

use Administrator\Form\AdministratorForm;

class MegabannerForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                MegabannerFieldset::class,
            ]
        ];
    }
}
