<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'aliases' => [
            'isAnchor' => \Administrator\Form\Element\YesNoSelect::class,
        ],
        'factories' => [
            \AmCustomer\Form\CustomerForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmCustomer\Form\CustomerFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'datatable' => [
        'factories' => [
            \AmCustomer\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class
        ]
    ],

    'service_manager' => [
    ],

    'router' => [
    ]
];
