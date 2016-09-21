<?php

namespace MediaDispatcher;

use MediaDispatcher\Controller\DispatcherController;

return array(

    'router' => array(
        'routes' => array(
            'dispatch' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/dispatch',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'random' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:rnd]',
                            'constraints' => array(
                                'rnd' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'MediaDispatcher\Controller',
                                'controller'    => 'Dispatcher',
                                'action'        => 'dispatch',
                            ),
                        ),
                    )
                )
            ),
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'dinamicImage' => 'MediaDispatcher\Service\ImageService'
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'MediaDispatcher\Controller\Dispatcher' => DispatcherController::class
        ),
    ),
);
