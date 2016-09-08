<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmAppData\Controller\AmAppDataModuleController' => 'AmAppData\Controller\AmAppDataModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);