<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'factories' => [
            \AmStaticPage\Form\StaticPageForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmStaticPage\Form\StaticPageFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmStaticPage\Form\StaticPageLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],

    'service_manager' => [
    ],

    'router' => [
    ]
];
