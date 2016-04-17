<?php
return array(

    'controllers' => array(),
    'service_manager' => array(),

    // controladores y demás
    'router' => array(
        'routes' => array(

            // Este mondongo se lo meto para capturar la /
            'barra' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Gestor\Factory\AdminControllerFactory',
                        'section' => 'login',
                        'action' => 'index'
                    ),
                ),
            ),
            // ------------ Por si peta la redirección de /, aqui acaba el mondongo ------------

            'mastah' => array(
                //'type' => 'literal',
                'type' => 'Zend\Mvc\Router\Http\Method',
                'options' => array(
                    //'route' => '/mastah',
                    //'route' => '/',
                    'verb' => 'post, put, get, delete',
                    'defaults' => array(
                        'controller' => 'Gestor\Factory\AdminControllerFactory',
                        'section' => 'login',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'sections' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '[/:section][/:action][/:id][/:langCode]',
                            'constraints' => array(
                                'section'  => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'       => '[0-9]+',
                                'langCode' => '[a-zA-Z]{2}',
                            ),
                            'defaults' => array(
                                'controller' => 'Gestor\Factory\AdminControllerFactory',
                                'action'     => 'index',
                            ),
                        ),
                    ),


                    /*
                    'default' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '',
                            'constraints' => array(
                                'section'  => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'       => '[0-9]+',
                                'langCode' => '[a-zA-Z]{2}',
                            ),
                            'defaults' => array(
                                'controller' => 'Gestor\Factory\AdminControllerFastory',
                                'section' => 'login',
                                'action' => 'index'
                            ),
                        ),
                    ),
                    */
/*
                    'default' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '[/:section][/:action][/:id][/:langCode]',
                            'constraints' => array(
                                'section'  => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'       => '[0-9]+',
                                'langCode' => '[a-zA-Z]{2}',
                            ),
                            'defaults' => array(
                                'controller' => 'Gestor\Factory\AdminControllerFactory',
                                'action'     => 'index',
                            ),
                        ),
                    ),
*/
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'gestor/index/index'      => __DIR__ . '/../view/gestor/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'base_path' => '/gestor'
    ),

    'session' => array(
        'remember_me_seconds' => 2419200,
        'use_cookies' => true,
        'cookie_httponly' => true,
    ),
    'translator' => array(
        'locale' => 'es_ES',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
);