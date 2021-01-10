<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'factories' => [
            \AmLanguage\Form\LanguageForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmLanguage\Form\LanguageFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ],
        'delegators' => [
            \AmLanguage\Form\LanguageFieldset::class => [
                \AmLanguage\Form\Factory\LanguageFieldsetDelegatorFactory::class
            ]
        ]
    ],
    'datatable' => [
        'factories' => [
            \AmLanguage\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
        ]
    ],
];
