<?php

namespace OpenGraph;

use OpenGraph\Service\OpenGraphService;
use Zend\ServiceManager\Factory\InvokableFactory;

return array(

    'service_manager' => array(
        'aliases' => [
            'OpenGraph' => OpenGraphService::class
        ],
        'factories' => array(
            OpenGraphService::class => InvokableFactory::class
        )
    )
);
