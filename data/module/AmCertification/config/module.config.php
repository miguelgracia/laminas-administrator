<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'factories' => [
            \AmCertification\Form\CertificationForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmCertification\Form\CertificationFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],
    'datatable' => [
        'factories' => [
            \AmCertification\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class
        ]
    ],
];
