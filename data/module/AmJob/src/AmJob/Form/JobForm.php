<?php

namespace AmJob\Form;

use Administrator\Form\AdministratorForm;

class JobForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                JobFieldset::class => array(),
                JobLocaleFieldset::class => array(),
            )
        );
    }

}

