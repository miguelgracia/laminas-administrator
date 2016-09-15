<?php

namespace AmBlog\Form;

use Administrator\Form\AdministratorForm;

class BlogForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                BlogFieldset::class => array(),
                BlogLocaleFieldset::class => array(),
            )
        );
    }
}