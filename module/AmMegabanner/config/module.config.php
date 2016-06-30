<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmMegabanner\Controller\AmMegabannerModuleController' => 'AmMegabanner\Controller\AmMegabannerModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);