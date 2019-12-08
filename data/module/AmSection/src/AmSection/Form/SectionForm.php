<?php

namespace AmSection\Form;

use Administrator\Form\AdministratorForm;

class SectionForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                SectionFieldset::class,
                SectionLocaleFieldset::class,
            ]
        ];
    }
}
