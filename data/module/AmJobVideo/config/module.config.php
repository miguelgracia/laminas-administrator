<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'controllers' => [
        'invokables' => [
            'AmJobVideo\Controller\AmJobVideoModuleController' => 'AmJobVideo\Controller\AmJobVideoModuleController'
        ]
    ],

    'service_manager' => [
    ],

    'router' => [
    ]
];
