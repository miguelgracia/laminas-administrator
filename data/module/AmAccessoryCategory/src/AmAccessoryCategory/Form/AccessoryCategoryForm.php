<?php

namespace AmAccessoryCategory\Form;

use Administrator\Form\AdministratorForm;

class AccessoryCategoryForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                AccessoryCategoryFieldset::class,
                AccessoryCategoryLocaleFieldset::class
            ]
        ];
    }
}
