<?php

namespace AmCertification\Form;

use Administrator\Form\AdministratorForm;

class CertificationForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                CertificationFieldset::class,
            ]
        ];
    }
}
