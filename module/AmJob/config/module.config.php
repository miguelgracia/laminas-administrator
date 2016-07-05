<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmJob\Controller\AmJobModuleController' => 'AmJob\Controller\AmJobModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);