<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmJobVideo\Controller\AmJobVideoModuleController' => 'AmJobVideo\Controller\AmJobVideoModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);