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
            'YoutubeService' => 'AmYouTube\Service\YoutubeService'
        )
    ),

    'router' => array(

    )
);