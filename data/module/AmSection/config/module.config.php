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
            \AmSection\Form\SectionForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmSection\Form\SectionFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmSection\Form\SectionLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],
    'datatable' => [
        'factories' => [
            \AmSection\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
        ]
    ],
    'service_manager' => [
    ],

    'router' => [
    ]
];
