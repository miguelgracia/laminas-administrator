<?php
return [
    'AmTool' => [
        'disable_usage' => false,    // set to true to disable showing available AmTool commands in Console.
    ],

    // -----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=

    'controllers' => [
        'invokables' => [
            'AmTool\Controller\Info' => 'AmTool\Controller\InfoController',
            'AmTool\Controller\Config' => 'AmTool\Controller\ConfigController',
            'AmTool\Controller\Module' => 'AmTool\Controller\ModuleController',
            'AmTool\Controller\Classmap' => 'AmTool\Controller\ClassmapController',
            'AmTool\Controller\Create' => 'AmTool\Controller\CreateController',
            'AmTool\Controller\Install' => 'AmTool\Controller\InstallController',
            'AmTool\Controller\Diagnostics' => 'AmTool\Controller\DiagnosticsController',
        ],
    ],

    'view_manager' => [
        'template_map' => [
            'zf-tool/diagnostics/run' => __DIR__ . '/../view/diagnostics/run.phtml',
        ]
    ],

    'console' => [
        'router' => [
            'routes' => [
                'zftool-version' => [
                    'options' => [
                        'route' => 'version',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Info',
                            'action' => 'version',
                        ],
                    ],
                ],
                'zftool-version2' => [
                    'options' => [
                        'route' => '--version',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Info',
                            'action' => 'version',
                        ],
                    ],
                ],
                'zftool-config-list' => [
                    'options' => [
                        'route' => 'config list [--local|-l]:local',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Config',
                            'action' => 'list',
                        ],
                    ],
                ],
                'zftool-config' => [
                    'options' => [
                        'route' => 'config <action> [<arg1>] [<arg2>]',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Config',
                            'action' => 'get',
                        ],
                    ],
                ],
                'zftool-classmap-generate' => [
                    'options' => [
                        'route' => 'classmap generate <directory> [<destination>] [--append|-a] [--overwrite|-w]',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Classmap',
                            'action' => 'generate',
                        ],
                    ],
                ],
                'zftool-modules-list' => [
                    'options' => [
                        'route' => 'modules [list]',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Module',
                            'action' => 'list',
                        ],
                    ],
                ],
                'zftool-create-project' => [
                    'options' => [
                        'route' => 'create project <path>',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Create',
                            'action' => 'project',
                        ],
                    ],
                ],
                'zftool-create-module' => [
                    'options' => [
                        'route' => 'create module <name> [<path>]',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Create',
                            'action' => 'module',
                        ],
                    ],
                ],
                'zftool-create-admin-module' => [
                    'options' => [
                        'route' => 'create am-module <name> [<path>]',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Create',
                            'action' => 'adminModule',
                        ],
                    ],
                ],
                'zftool-add-views-admin-module' => [
                    'options' => [
                        'route' => 'create am-view-module <name> [<path>]',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Create',
                            'action' => 'adminViews',
                        ],
                    ],
                ],
                'zftool-locale-classes-admin-module' => [
                    'options' => [
                        'route' => 'create am-locale-classes <name> [<path>]',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Create',
                            'action' => 'adminLocaleClasses',
                        ],
                    ],
                ],
                'zftool-create-controller' => [
                    'options' => [
                        'route' => 'create controller <name> <module> [<path>]',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Create',
                            'action' => 'controller',
                        ],
                    ],
                ],
                'zftool-create-action' => [
                    'options' => [
                        'route' => 'create action <name> <controllerName> <module>',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Create',
                            'action' => 'method',
                        ],
                    ],
                ],
                'zftool-install-zf' => [
                    'options' => [
                        'route' => 'install zf <path> [<version>]',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Install',
                            'action' => 'zf',
                        ],
                    ],
                ],
                'zftool-diagnostics' => [
                    'options' => [
                        'route' => '(diagnostics|diag) [-v|--verbose]:verbose [--debug] [-q|--quiet]:quiet [-b|--break]:break [<filter>]',
                        'defaults' => [
                            'controller' => 'AmTool\Controller\Diagnostics',
                            'action' => 'run',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'diagnostics' => [
        'ZF' => [
            'PHP Version' => ['PhpVersion', '5.3.3'],
        ]
    ]
];
