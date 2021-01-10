<?php

return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'factories' => [
            \AmModule\Form\ModuleForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmModule\Form\ModuleFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'service_manager' => [
        'factories' => [
            'AmModule\Service\ModuleService' => 'AmModule\Service\ModuleService'
        ]
    ],
    'datatable' => [
        'factories' => [
            \AmModule\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
        ]
    ],
];
