<?php

namespace AmLanguage\Form;

use Administrator\Form\AdministratorForm;

class LanguageForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                LanguageFieldset::class => array(
                    'use_as_base_fieldset' => true
                )
            )
        );
    }
}