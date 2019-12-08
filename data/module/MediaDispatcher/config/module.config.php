<?php

namespace MediaDispatcher;

use MediaDispatcher\Controller\DispatcherController;
use MediaDispatcher\Service\ImageService;
use MediaDispatcher\View\Helper\DinamicUrlImage;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'dispatch' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/dispatch',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'random' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/[:rnd]',
                            'constraints' => [
                                'rnd' => '[a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                '__CONTROLLER__' => 'MediaDispatcher',
                                'controller' => DispatcherController::class,
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
            'dinamicImage' => ImageService::class
        ]
    ],
    'controllers' => [
        'factories' => [
            DispatcherController::class => InvokableFactory::class
        ]
    ],
    'view_helpers' => [
        'factories' => [
            'dinamicImageHelper' => DinamicUrlImage::class
        ]
    ]
];
