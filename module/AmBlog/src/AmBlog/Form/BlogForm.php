<?php

namespace AmBlog\Form;

use Administrator\Form\AdministratorForm;

class BlogForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                BlogFieldset::class => array(
                    'use_as_base_fieldset' => true
                ),
                BlogLocaleFieldset::class => array(),
            )
        );
    }
}