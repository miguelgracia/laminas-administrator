<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'aliases' => [
            'address' => \Administrator\Form\Element\SimpleTextarea::class,
        ],
        'factories' => [
            \AmAppData\Form\AppDataForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmAppData\Form\AppDataFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmAppData\Form\AppDataLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],
    'datatable' => [
        'factories' => [
            \AmAppData\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
        ]
    ],
    'service_manager' => [
    ],

    'router' => [
    ]
];
