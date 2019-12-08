<?php

namespace AmJobCategory\Form;

use Administrator\Form\AdministratorForm;

class JobCategoryForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                JobCategoryFieldset::class,
                JobCategoryLocaleFieldset::class
            ]
        ];
    }
}
