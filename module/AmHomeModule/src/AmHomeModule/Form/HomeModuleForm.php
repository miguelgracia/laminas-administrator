<?php

namespace AmHomeModule\Form;

use Administrator\Form\AdministratorForm;

class HomeModuleForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                HomeModuleFieldset::class => array(),
                HomeModuleLocaleFieldset::class => array(),
            )
        );
    }

}

