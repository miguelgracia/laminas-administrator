<?php

namespace AmBlog\Form;

use Administrator\Form\AdministratorForm;

class BlogForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                BlogFieldset::class,
                BlogLocaleFieldset::class,
            ]
        ];
    }
}
