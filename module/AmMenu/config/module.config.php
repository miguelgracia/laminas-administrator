<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmMenu\Controller\AmMenuModuleController' => 'AmMenu\Controller\AmMenuModuleController'
        )
    ),

    'service_manager' => array(
        'factories' => array(
            'Navigation' => 'AmMenu\Factory\MenuNavigationFactory',
        )
    ),

    'router' => array(

    )
);