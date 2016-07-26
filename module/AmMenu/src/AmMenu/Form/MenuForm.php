<?php

namespace AmMenu\Form;

use Administrator\Form\AdministratorForm;

class MenuForm extends AdministratorForm {

    public function initializers()
    {
        return array(
            'fieldsets' => array(
                MenuFieldset::class => array()
            )
        );
    }
}