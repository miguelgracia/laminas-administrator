<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'aliases' => [
            'blogCategoriesId' => \Zend\Form\Element\Hidden::class,
        ],
        'factories' => [
            \AmBlogCategory\Form\BlogCategoryForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmBlogCategory\Form\BlogCategoryFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmBlogCategory\Form\BlogCategoryLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class,
        ]
    ],
];
