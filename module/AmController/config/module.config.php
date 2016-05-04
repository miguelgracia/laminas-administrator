<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmController\Controller\AmControllerModuleController' => 'AmController\Controller\AmControllerModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(
// Uncomment below to add routes
//        'routes' => array(
//            'amcontroller' => array(
//                'type' => 'Literal',
//                'options' => array(
//                    'route' => '/amcontroller',
//                    'defaults' => array(
//                        '__NAMESPACE__' => 'AmController\Controller',
//                        'controller' => 'AmController',
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