<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmModule\Controller\AmModuleModuleController' => 'AmModule\Controller\AmModuleModuleController'
        )
    ),

    'service_manager' => array(
        'factories' => array(
            'AmModule\Service\ModuleService' => 'AmModule\Service\ModuleService'
        )
    ),

    'router' => array(

    )
);