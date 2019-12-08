<?php

namespace MediaDispatcher;

use MediaDispatcher\Controller\DispatcherController;
use MediaDispatcher\View\Helper\DinamicUrlImage;

return [
    'router' => [
        'routes' => [
            'dispatch' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/dispatch',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'random' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/[:rnd]',
                            'constraints' => [
                                'rnd' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'MediaDispatcher\Controller',
                                'controller' => 'Dispatcher',
                                'action' => 'dispatch',
                            ],
                        ],
                    ]
                ]
            ],
        ]
    ],
    'service_manager' => [
        'factories' => [
            'dinamicImage' => 'MediaDispatcher\Service\ImageService'
        ]
    ],
    'controllers' => [
        'invokables' => [
            'MediaDispatcher\Controller\Dispatcher' => DispatcherController::class
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'dinamicImageHelper' => DinamicUrlImage::class
        ]
    ]
];
