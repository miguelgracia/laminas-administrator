<?php

namespace AmBlogCategory\Form;

use Administrator\Form\AdministratorForm;

class BlogCategoryForm extends AdministratorForm
{
    public function initializers()
    {
        return array(
            'fieldsets' => array(
                BlogCategoryFieldset::class,
                BlogCategoryLocaleFieldset::class
            )
        );
    }
}
