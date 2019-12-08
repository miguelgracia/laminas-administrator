<?php

return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'form_elements' => [
        'factories' => [
            \AmBlog\Form\Element\BlogCategoriesId::class => \AmBlog\Form\Element\BlogCategoriesIdFactory::class,
            \AmBlog\Form\BlogForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmBlog\Form\BlogFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmBlog\Form\BlogLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class,
        ]
    ],
];
