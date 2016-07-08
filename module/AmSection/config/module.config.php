<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmSection\Controller\AmSectionModuleController' => 'AmSection\Controller\AmSectionModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);