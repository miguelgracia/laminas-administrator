<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\CompanyController;
use Application\Controller\ContactController;
use Application\Controller\HomeController;
use Application\Controller\JobController;
use Application\Controller\LegalController;
use Application\View\Helper\Blog;
use Application\View\Helper\BlogCategory;
use Application\View\Helper\CarouselItem;
use Application\View\Helper\ContactForm;
use Application\View\Helper\FacebookShare;
use Application\View\Helper\Gallery;
use Application\View\Helper\JobCategory;
use Application\View\Helper\LegalLink;
use Application\View\Helper\Menu;
use Application\View\Helper\MenuDelegator;
use Application\View\Helper\Partner;
use Application\View\Helper\SocialIcon;
use Zend\I18n\Translator\Resources;
use Zend\I18n\Translator\TranslatorServiceFactory;
use Zend\I18n\View\Helper\Translate;
use Zend\Mvc\I18n\Router\TranslatorAwareTreeRouteStack;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Session\Storage\SessionArrayStorage;

return [
    'languages_by_host' => [
        'bravo-consultoria.local' => ['es', 'en'],
        'bravo-silva.ngrok.io' => ['es', 'en'],
        'absconsultor.es' => ['es', 'en'],
        'absconsultor.local' => ['es', 'en'],
    ],
    'media_base_url' => [
        'absconsultor.local' => 'http://media.absconsultor.local',
        'absconsultor.es' => 'http://media.absconsultor.es',
    ],
    'router' => [
        'router_class' => TranslatorAwareTreeRouteStack::class,
        'translator_text_domain' => 'routing',
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        '__CONTROLLER__' => 'Home',
                        'controller' => HomeController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'locale' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[:locale]',
                    'constraints' => [
                        'locale' => '(es|en)+'
                    ],
                    'defaults' => [
                        '__CONTROLLER__' => 'Home',
                        'controller' => HomeController::class,
                        'action' => 'index',
                        'locale' => 'es'
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'jobs' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/{jobs}',
                            'defaults' => [
                                '__CONTROLLER__' => 'Job',
                                'controller' => JobController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'category' => [
                                'type' => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route' => '/[:category]',
                                    'constraints' => [
                                        'category' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ],
                                    'defaults' => [
                                        '__CONTROLLER__' => 'Job',
                                        'controller' => JobController::class,
                                        'action' => 'category',
                                    ],
                                ],
                                'child_routes' => [
                                    'detail' => [
                                        'type' => 'Segment',
                                        'options' => [
                                            'route' => '/[:detail]',
                                            'constraints' => [
                                                'detail' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                            ],
                                            'defaults' => [
                                                '__CONTROLLER__' => 'Job',
                                                'controller' => JobController::class,
                                                'action' => 'detail',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'contact' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/{contact}',
                            'defaults' => [
                                '__CONTROLLER__' => 'Contact',
                                'controller' => HomeController::class,
                                'action' => 'contact',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'legal' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/legales' // legals
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'page' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/[:page]',
                                    'constraints' => [
                                        'page' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ],
                                    'defaults' => [
                                        '__CONTROLLER__' => 'Legal',
                                        'controller' => LegalController::class,
                                        'action' => 'index',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ],
        ]
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Home' => Controller\HomeController::class,
            'Application\Controller\Job' => Controller\JobController::class,
            'Application\Controller\Contact' => Controller\ContactController::class,
            'Application\Controller\Company' => Controller\CompanyController::class,
            'Application\Controller\Legal' => Controller\LegalController::class,
        ],
    ],

    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'session_containers' => [
        'frontend'
    ],
    'session_config' => [
        'remember_me_seconds' => 180,
        'use_cookies' => true,
        'cookie_httponly' => true,
    ],
    'service_manager' => [
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'factories' => [
            'Translator' => TranslatorServiceFactory::class,
            'Application\Service\SessionService' => 'Application\Service\SessionService',
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'applicationMenuHelper' => Menu::class,
            'socialIconHelper' => SocialIcon::class,
            'legalLinkHelper' => LegalLink::class,
            'partnerHelper' => Partner::class,
            'galleryHelper' => Gallery::class,
            'jobCategoryHelper' => JobCategory::class,
            'contactFormHelper' => ContactForm::class,
            'carouselItemHelper' => CarouselItem::class,
            'facebookShareHelper' => FacebookShare::class,
            'translate' => Translate::class
        ],
        'factories' => [
            Menu::class => InvokableFactory::class,
            SocialIcon::class => InvokableFactory::class,
            LegalLink::class => InvokableFactory::class,
            Partner::class => InvokableFactory::class,
            Gallery::class => InvokableFactory::class,
            JobCategory::class => InvokableFactory::class,
            ContactForm::class => ContactForm::class,
            CarouselItem::class => CarouselItem::class,
            FacebookShare::class => InvokableFactory::class,
            Translate::class => InvokableFactory::class
        ],
        'delegators' => [
            Menu::class => [
                MenuDelegator::class
            ]
        ],
    ],
    'translator' => [
        'locale' => 'es',
        'translation_file_patterns' => [
            [
                'type' => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php',
                //'text_domain' => 'frontend'
            ],
            [
                'type' => 'phpArray',
                'base_dir' => __DIR__ . '/../language/routing',
                'pattern' => '%s.php',
                'text_domain' => 'routing'
            ],
            [
                'type' => 'phpArray',
                'base_dir' => Resources::getBasePath(),
                'pattern' => Resources::getPatternForValidator(),
                //'text_domain' => 'formvalidation'
            ],
        ],
    ],
    'view_manager' => [
        'base_path' => 'http://absconsultor.es/',
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/front-layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            'application' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy'
        ]
    ],
];
