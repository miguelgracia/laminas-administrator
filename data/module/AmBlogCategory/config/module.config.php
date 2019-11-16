<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'factories' => [
            \AmBlogCategory\Form\BlogCategoryForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmBlogCategory\Form\BlogCategoryFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmBlogCategory\Form\BlogCategoryLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class,
        ]
    ],

    'service_manager' => array(

    ),

    'router' => array(

    )
);