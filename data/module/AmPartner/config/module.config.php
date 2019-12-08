<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'factories' => [
            \AmPartner\Form\PartnerForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmPartner\Form\PartnerFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'service_manager' => [
    ],

    'router' => [
    ]
];
