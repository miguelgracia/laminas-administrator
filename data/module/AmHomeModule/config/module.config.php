<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'factories' => [
            \AmHomeModule\Form\HomeModuleForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmHomeModule\Form\HomeModuleFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmHomeModule\Form\HomeModuleLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],

    'service_manager' => [
    ],

    'router' => [
    ]
];
