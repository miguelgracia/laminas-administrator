<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'aliases' => [
            'accessoryCategoriesId' => \Laminas\Form\Element\Hidden::class,
        ],
        'factories' => [
            \AmAccessoryCategory\Form\AccessoryCategoryForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmAccessoryCategory\Form\AccessoryCategoryFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmAccessoryCategory\Form\AccessoryCategoryLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],
    'datatable' => [
        'factories' => [
            \AmAccessoryCategory\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
        ]
    ],
    'service_manager' => [
    ],

    'router' => [
    ]
];
