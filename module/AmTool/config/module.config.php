<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(

    ),

    'service_manager' => array(

    ),

    'router' => array(
// Uncomment below to add routes
//        'routes' => array(
//            'amtool' => array(
//                'type' => 'Literal',
//                'options' => array(
//                    'route' => '/amtool',
//                    'defaults' => array(
//                        '__NAMESPACE__' => 'AmTool\Controller',
//                        'controller' => 'AmTool',
//                        'action' => 'index',
//                    )
//                )
//            )
//        ),
//        'may_terminate' => true,
//        'child_routes' => array(
//            'default' => array(
//                'type' => 'Segment',
//                'options' => array(
//                    'route' => '/[:controller[/:action]]',
//                    'constraints' => array(
//                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                    )
//                )
//            )
//        )
    )
);