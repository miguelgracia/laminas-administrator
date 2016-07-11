<?php

namespace AmStaticPage\Form;

use Administrator\Form\AdministratorForm;

class StaticPageForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                StaticPageFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
                StaticPageLocaleFieldset::class => array(),
            )
        );
    }
}