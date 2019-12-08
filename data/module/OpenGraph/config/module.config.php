<?php

namespace OpenGraph;

use OpenGraph\Service\OpenGraphService;
use OpenGraph\View\Helper\OpenGraphTag;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'aliases' => [
            'OpenGraph' => OpenGraphService::class
        ],
        'factories' => [
            OpenGraphService::class => InvokableFactory::class
        ]
    ],
    'view_helpers' => [
        'factories' => [
            'OpenGraphTag' => OpenGraphTag::class
        ]
    ]
];
