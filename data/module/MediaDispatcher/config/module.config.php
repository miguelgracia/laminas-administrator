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
                    'media_type' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:media-type]',
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'path' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/[:path]',
                                    'defaults' => array(
                                        '__NAMESPACE__' => 'MediaDispatcher\Controller',
                                        'controller'    => 'Dispatcher',
                                        'action'        => 'dispatch',
                                    ),
                                )
                            )
                        )
                    )
                )
            ),
        )
    ),
    'service_manager' => array(

    ),
    'controllers' => array(
        'invokables' => array(
            'MediaDispatcher\Controller\Dispatcher' => DispatcherController::class
        ),
    ),
);
