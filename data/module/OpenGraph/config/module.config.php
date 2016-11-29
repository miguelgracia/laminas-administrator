<?php

namespace OpenGraph;

use OpenGraph\Service\OpenGraphService;

return array(

    'service_manager' => array(
        'factories' => array(
            'OpenGraph' => OpenGraphService::class
        )
    )
);
