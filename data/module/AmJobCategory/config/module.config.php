<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'aliases' => [
            'jobCategoriesId' => \Laminas\Form\Element\Hidden::class,
        ],
        'factories' => [
            \AmJobCategory\Form\JobCategoryForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmJobCategory\Form\JobCategoryFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmJobCategory\Form\JobCategoryLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],
    'datatable' => [
        'factories' => [
            \AmJobCategory\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
        ]
    ],
    'service_manager' => [
    ],

    'router' => [
    ]
];
