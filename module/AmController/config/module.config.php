<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmController\Controller\AmControllerModuleController' => 'AmController\Controller\AmControllerModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);