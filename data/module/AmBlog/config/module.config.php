<?php

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmBlog\Controller\AmBlogModuleController' => 'AmBlog\Controller\AmBlogModuleController'
        )
    ),

    'form_elements' => [
        'factories' => [
            \AmBlog\Form\BlogForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmBlog\Form\BlogFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmBlog\Form\BlogLocaleFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'service_manager' => array(),

    'router' => array()
);