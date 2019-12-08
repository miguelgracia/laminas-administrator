<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'factories' => [
            \AmMegabanner\Form\MegabannerForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmMegabanner\Form\MegabannerFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'service_manager' => [
    ],

    'router' => [
    ]
];
