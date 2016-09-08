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
        'abs.local'          => ['es_ES','en_EN'],
        'absconsultor.local' => ['es_ES','en_EN'],
        'absconsultor.es'    => ['es_ES','en_EN'],
        'absconsultor.com'   => ['en_EN','es_ES']
    ),
    'router' => array(
        'router_class' => LocaleTreeRouteStack::class,
        'frontend_routes_locale' => array(
            'lang' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/[:lang]',
                    'constraints' => array(
                        'lang' => array(
                            'es_ES' => 'es_es',
                            'en_EN' => 'en_en',
                        )
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
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:section]',
                            'constraints' => array(
                                'section' => array(
                                    'es_ES' => 'empresa',
                                    'en_EN' => 'company',
                                )
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller'    => 'Company',
                                'action'        => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'collaborators' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:colaborators]',
                                    'constraints' => array(
                                        'colaborators' => array(
                                            'es_ES' => 'colaboradores',
                                            'en_EN' => 'colaborators',
                                        )
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
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:section]',
                            'constraints' => array(
                                'section' => array(
                                    'es_ES' => 'trabajos',
                                    'en_EN' => 'works',
                                )
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
                                    'route' => '/[:slug-category]',
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
                    'contact' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:section]',
                            'constraints' => array(
                                'section' => array(
                                    'es_ES' => 'contacto',
                                    'en_EN' => 'contact',
                                ),
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
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:section]',
                            'constraints' => array(
                                'section' => array(
                                    'es_ES' => 'legales',
                                    'en_EN' => 'legal',
                                )
                            )
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'page' => array(
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
