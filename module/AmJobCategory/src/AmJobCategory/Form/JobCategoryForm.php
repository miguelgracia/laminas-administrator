<?php

namespace AmJobCategory\Form;

use Administrator\Form\AdministratorForm;

class JobCategoryForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                JobCategoryFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
                JobCategoryLocaleFieldset::class => array()
            )
        );
    }
}