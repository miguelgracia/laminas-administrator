<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'factories' => [
            \AmSection\Form\SectionForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmSection\Form\SectionFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmSection\Form\SectionLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],

    'service_manager' => [
    ],

    'router' => [
    ]
];
