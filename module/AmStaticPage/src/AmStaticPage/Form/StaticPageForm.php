<?php

namespace AmStaticPage\Form;

use Administrator\Form\AdministratorForm;

class StaticPageForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                StaticPageFieldset::class => array(),
                StaticPageLocaleFieldset::class => array(),
            )
        );
    }
}