<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'home' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Home',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),
            'jobs' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/trabajos',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Job',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'category' => array(
                        'type'    => 'Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route'    => '/[:slug-category]',
                            'constraints' => array(
                                'slug-category' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller'    => 'Job',
                                'action'        => 'category',
                            ),
                        ),
                        'child_routes' => array(
                            'detail' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:slug-title]',
                                    'constraints' => array(
                                        'slug-title'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        '__NAMESPACE__' => 'Application\Controller',
                                        'controller'    => 'Job',
                                        'action'        => 'detail',
                                    ),
                                ),
                            ),
                        )
                    ),
                ),
            ),
            'blog' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/blog',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Blog',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'category' => array(
                        'type'    => 'Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route'    => '/[:slug-category]',
                            'constraints' => array(
                                'slug-category' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller'    => 'Blog',
                                'action'        => 'category',
                            ),
                        ),
                        'child_routes' => array(
                            'detail' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:slug-title]',
                                    'constraints' => array(
                                        'slug-title'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        '__NAMESPACE__' => 'Application\Controller',
                                        'controller'    => 'Blog',
                                        'action'        => 'detail',
                                    ),
                                ),
                            ),
                        )
                    ),
                ),
            ),
            'company' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/empresa',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Company',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'collaborators' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/colaboradores',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller'    => 'Company',
                                'action'        => 'collaborators',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                )
            ),
            'contact' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/contacto',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Contact',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),
            'legal' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/legales',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'coookies' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:legal-page]',
                            'constraints' => array(
                                'legal-page'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller'    => 'Legal',
                                'action'        => 'index',
                            ),
                        ),
                    ),
                )
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
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
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Home'    => Controller\HomeController::class,
            'Application\Controller\Job'     => Controller\JobController::class,
            'Application\Controller\Blog'    => Controller\BlogController::class,
            'Application\Controller\Contact' => Controller\ContactController::class,
            'Application\Controller\Company' => Controller\CompanyController::class,
            'Application\Controller\Legal'   => Controller\LegalController::class,
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/front-layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy'
        )
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
