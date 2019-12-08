<?php

namespace AmStaticPage\Form;

use Administrator\Form\AdministratorForm;

class StaticPageForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                StaticPageFieldset::class,
                StaticPageLocaleFieldset::class,
            ]
        ];
    }
}
