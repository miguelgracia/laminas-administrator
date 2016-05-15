<?php

return array(

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/admin-layout'       => __DIR__ . '/../view/layout/admin-layout.phtml',
            'layout/admin-login-layout' => __DIR__ . '/../view/layout/admin-login-layout.phtml',
            //'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            //'error/404'               => __DIR__ . '/../view/error/404.phtml',
            //'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(

    ),

    'service_manager' => array(

    ),

    'router' => array(

        'routes' => array(
            'administrator' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/admin[/[:module[/[:action[/[:id]]]]]]',
                    'defaults' => array(
                        'controller' => 'Administrator\Factory\AdminControllerFactory',
                        'action' => 'index'
                    )
                )
            )
        ),
    )
);