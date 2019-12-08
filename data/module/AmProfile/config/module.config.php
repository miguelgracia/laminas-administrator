<?php

return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'aliases' => [
            'isAdmin' => \Administrator\Form\Element\YesNoSelect::class,
            'description' => \Zend\Form\Element\Textarea::class,
        ],
        'factories' => [
            \AmProfile\Form\Element\Permissions::class => \AmProfile\Form\Element\PermissionsFactory::class,
            \AmProfile\Form\ProfileForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmProfile\Form\ProfileFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'service_manager' => [
        'factories' => [
            \AmProfile\Service\ProfilePermissionService::class => \AmProfile\Service\ProfilePermissionServiceFactory::class,
        ]
    ],
];
