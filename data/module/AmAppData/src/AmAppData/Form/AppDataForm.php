<?php

namespace AmAppData\Form;

use Administrator\Form\AdministratorForm;

class AppDataForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                AppDataFieldset::class,
                AppDataLocaleFieldset::class,
            )
        );
    }
}