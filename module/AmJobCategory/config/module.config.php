<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmJobCategory\Controller\AmJobCategoryModuleController' => 'AmJobCategory\Controller\AmJobCategoryModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);