<?php
return array(
    'AmTool' => array(
        'disable_usage' => false,    // set to true to disable showing available AmTool commands in Console.
    ),

    // -----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=

    'controllers' => array(
        'invokables' => array(
            'AmTool\Controller\Info'        => 'AmTool\Controller\InfoController',
            'AmTool\Controller\Config'      => 'AmTool\Controller\ConfigController',
            'AmTool\Controller\Module'      => 'AmTool\Controller\ModuleController',
            'AmTool\Controller\Classmap'    => 'AmTool\Controller\ClassmapController',
            'AmTool\Controller\Create'      => 'AmTool\Controller\CreateController',
            'AmTool\Controller\Install'     => 'AmTool\Controller\InstallController',
            'AmTool\Controller\Diagnostics' => 'AmTool\Controller\DiagnosticsController',
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'zf-tool/diagnostics/run' => __DIR__ . '/../view/diagnostics/run.phtml',
        )
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'zftool-version' => array(
                    'options' => array(
                        'route'    => 'version',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Info',
                            'action'     => 'version',
                        ),
                    ),
                ),
                'zftool-version2' => array(
                    'options' => array(
                        'route'    => '--version',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Info',
                            'action'     => 'version',
                        ),
                    ),
                ),
                'zftool-config-list' => array(
                    'options' => array(
                        'route'    => 'config list [--local|-l]:local',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Config',
                            'action'     => 'list',
                        ),
                    ),
                ),
                'zftool-config' => array(
                    'options' => array(
                        'route'    => 'config <action> [<arg1>] [<arg2>]',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Config',
                            'action'     => 'get',
                        ),
                    ),
                ),
                'zftool-classmap-generate' => array(
                    'options' => array(
                        'route'    => 'classmap generate <directory> [<destination>] [--append|-a] [--overwrite|-w]',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Classmap',
                            'action'     => 'generate',
                        ),
                    ),
                ),
                'zftool-modules-list' => array(
                    'options' => array(
                        'route'    => 'modules [list]',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Module',
                            'action'     => 'list',
                        ),
                    ),
                ),
                'zftool-create-project' => array(
                    'options' => array(
                        'route'    => 'create project <path>',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Create',
                            'action'     => 'project',
                        ),
                    ),
                ),
                'zftool-create-module' => array(
                    'options' => array(
                        'route'    => 'create module <name> [<path>]',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Create',
                            'action'     => 'module',
                        ),
                    ),
                ),
                'zftool-create-admin-module' => array(
                    'options' => array(
                        'route'    => 'create am-module <name> [<path>]',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Create',
                            'action'     => 'adminModule',
                        ),
                    ),
                ),
                'zftool-create-controller' => array(
                    'options' => array(
                        'route'    => 'create controller <name> <module> [<path>]',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Create',
                            'action'     => 'controller',
                        ),
                    ),
                ),
                'zftool-create-action' => array(
                    'options' => array(
                        'route'    => 'create action <name> <controllerName> <module>',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Create',
                            'action'     => 'method',
                        ),
                    ),
                ),
                'zftool-install-zf' => array(
                    'options' => array(
                        'route'    => 'install zf <path> [<version>]',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Install',
                            'action'     => 'zf',
                        ),
                    ),
                ),
                'zftool-diagnostics' => array(
                    'options' => array(
                        'route'    => '(diagnostics|diag) [-v|--verbose]:verbose [--debug] [-q|--quiet]:quiet [-b|--break]:break [<filter>]',
                        'defaults' => array(
                            'controller' => 'AmTool\Controller\Diagnostics',
                            'action'     => 'run',
                        ),
                    ),
                ),
            ),
        ),
    ),

    'diagnostics' => array(
        'ZF' => array(
            'PHP Version' => array('PhpVersion', '5.3.3'),
        )
    )
);
