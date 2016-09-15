<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmHomeModule\Controller\AmHomeModuleModuleController' => 'AmHomeModule\Controller\AmHomeModuleModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);