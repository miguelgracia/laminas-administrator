<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmProfile\Controller\AmProfileModuleController' => 'AmProfile\Controller\AmProfileModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);