<?php

namespace AmCustomer\Form;

use Administrator\Form\AdministratorForm;

class CustomerForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                CustomerFieldset::class,
            ]
        ];
    }
}
