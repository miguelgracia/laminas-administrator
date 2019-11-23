<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmYouTube\Controller\AmYouTubeModuleController' => 'AmYouTube\Controller\AmYouTubeModuleController'
        )
    ),

    'service_manager' => array(
        'factories' => array(
            \AmYouTube\Service\YoutubeService::class => \AmYouTube\Service\YoutubeService::class
        )
    ),

    'form_elements' => [
        'factories' => [
            \AmYouTube\Form\Element\Visibility::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
            \AmUser\Form\AmUserForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmUser\Form\UserFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class
        ]
    ],
);