<?php

namespace AmUser\Form;

use Administrator\Form\AdministratorForm;

class AmUserForm extends AdministratorForm {

    public function initializers()
    {
        return array(
            'fieldsets' => array(
                UserFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
            )
        );
    }
}