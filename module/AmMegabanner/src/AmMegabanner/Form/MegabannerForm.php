<?php

namespace AmMegabanner\Form;

use Administrator\Form\AdministratorForm;

class MegabannerForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                MegabannerFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
                MegabannerLocaleFieldset::class => array(),
            )
        );
    }
}

