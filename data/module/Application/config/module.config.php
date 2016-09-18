<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Router\Http\LocaleTreeRouteStack;

return array(
    'languages_by_host' => array(
        'abs.local'          => ['es_es','en_en'],
        'absconsultor.local' => ['es_es','en_en'],
        'absconsultor.es'    => ['es_es','en_en'],
        'absconsultor.com'   => ['en_en','es_es']
    ),
    'media_base_url' => array(
        'abs.local'          => 'http://media.abs.local',
        'absconsultor.local' => 'http://media.absconsultor.local',
        'absconsultor.es'    => 'http://media.absconsultor.es',
        'absconsultor.com'   => 'http://media.absconsultor.com'
    ),
    'router' => array(
        'router_class' => LocaleTreeRouteStack::class,
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
        'frontend_routes_locale' => array(
            'lang' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => array(
                        'es_es' => '/es_es',
                        'en_en' => '/en_en',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Home',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'company' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => array(
                                'es_es' => '/empresa',
                                'en_en' => '/company',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller'    => 'Company',
                                'action'        => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'colaborators' => array(
                                'type'    => 'Literal',
                                'options' => array(
                                    'route'    => array(
                                        'es_es' => '/colaboradores',
                                        'en_en' => '/colaborators',
                                    ),
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
                    'jobs' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => array(
                                'es_es' => '/trabajos',
                                'en_en' => '/jobs',
                            ),
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
                                    'route' => '/[:category]',
                                    'constraints' => array(
                                        'category' => '[a-zA-Z][a-zA-Z0-9_-]*',
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
                                            'route'    => '/[:detail]',
                                            'constraints' => array(
                                                'detail'    => '[a-zA-Z][a-zA-Z0-9_-]*',
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
                                    'route'    => '/[:category]',
                                    'constraints' => array(
                                        'category' => '[a-zA-Z][a-zA-Z0-9_-]*',
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
                                            'route'    => '/[:detail]',
                                            'constraints' => array(
                                                'detail'    => '[a-zA-Z][a-zA-Z0-9_-]*',
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
                    'contact' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => array(
                                'es_es' => '/contacto',
                                'en_en' => '/contact',
                            ),
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
                            'route'    => array(
                                'es_es' => '/legales',
                                'en_en' => '/legal',
                            ),
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'page' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:page]',
                                    'constraints' => array(
                                        'page'    => '[a-zA-Z][a-zA-Z0-9_-]*',
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
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator'                         => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'Application\Service\SessionService' => 'Application\Service\SessionService',
        ),
    ),
    'translator' => array(
        'locale' => 'es_ES',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
                'text_domain' => 'frontend'
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Application' => Controller\ApplicationController::class,
            'Application\Controller\Home'        => Controller\HomeController::class,
            'Application\Controller\Job'         => Controller\JobController::class,
            'Application\Controller\Blog'        => Controller\BlogController::class,
            'Application\Controller\Contact'     => Controller\ContactController::class,
            'Application\Controller\Company'     => Controller\CompanyController::class,
            'Application\Controller\Legal'       => Controller\LegalController::class,
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
