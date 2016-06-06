<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmMedia\Controller\AmMediaModuleController' => 'AmMedia\Controller\AmMediaModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);