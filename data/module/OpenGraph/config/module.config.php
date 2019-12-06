<?php

namespace OpenGraph;

use OpenGraph\Service\OpenGraphService;
use OpenGraph\View\Helper\OpenGraphTag;
use Zend\ServiceManager\Factory\InvokableFactory;

return array(

    'service_manager' => array(
        'aliases' => [
            'OpenGraph' => OpenGraphService::class
        ],
        'factories' => array(
            OpenGraphService::class => InvokableFactory::class
        )
    ),
    'view_helpers' => [
        'factories' => array(
            'OpenGraphTag' => OpenGraphTag::class
        )
    ]
);
