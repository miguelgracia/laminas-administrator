<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'controllers' => [
        'invokables' => [
            'AmYouTube\Controller\AmYouTubeModuleController' => 'AmYouTube\Controller\AmYouTubeModuleController'
        ]
    ],

    'service_manager' => [
        'factories' => [
            \AmYouTube\Service\YoutubeService::class => \AmYouTube\Service\YoutubeService::class
        ]
    ],

    'form_elements' => [
        'factories' => [
            \AmYouTube\Form\Element\Visibility::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            \AmUser\Form\AmUserForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmUser\Form\UserFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class
        ]
    ],
];
