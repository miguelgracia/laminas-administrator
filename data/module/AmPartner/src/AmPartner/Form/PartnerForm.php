<?php

namespace AmPartner\Form;

use Administrator\Form\AdministratorForm;

class PartnerForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                PartnerFieldset::class => array(),
            )
        );
    }
}