<?php

namespace AmSection\Form;

use Administrator\Form\AdministratorForm;

class SectionForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                SectionFieldset::class,
                SectionLocaleFieldset::class,
            )
        );
    }
}