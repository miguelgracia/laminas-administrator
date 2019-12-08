<?php

namespace AmBlogCategory\Form;

use Administrator\Form\AdministratorForm;

class BlogCategoryForm extends AdministratorForm
{
    public function initializers()
    {
        return [
            'fieldsets' => [
                BlogCategoryFieldset::class,
                BlogCategoryLocaleFieldset::class
            ]
        ];
    }
}
