<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\ContactController;
use Application\Controller\HomeController;
use Application\Controller\JobController;
use Application\Controller\AccessoryController;
use Application\Controller\LegalController;
use Application\Service\GalleryRenderService;
use Application\Service\SessionService;
use Application\View\Helper\CarouselItem;
use Application\View\Helper\CertificationItem;
use Application\View\Helper\ContactForm;
use Application\View\Helper\FacebookShare;
use Application\View\Helper\Gallery;
use Application\View\Helper\JobCategory;
use Application\View\Helper\LegalLink;
use Application\View\Helper\Menu;
use Application\View\Helper\MenuDelegator;
use Application\View\Helper\Partner;
use Application\View\Helper\SocialIcon;
use Laminas\Cache\Service\StorageCacheAbstractServiceFactory;
use Laminas\I18n\Translator\Resources;
use Laminas\I18n\Translator\TranslatorServiceFactory;
use Laminas\I18n\View\Helper\Translate;
use Laminas\Log\LoggerAbstractServiceFactory;
use Laminas\Mvc\I18n\Router\TranslatorAwareTreeRouteStack;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Session\Storage\SessionArrayStorage;

return [
    'languages_by_host' => [
        'bravo-consultoria.local' => ['es', 'en'],
        'bravo-silva.ngrok.io' => ['es', 'en'],
        'absconsultor.es' => ['es', 'en'],
        'absconsultor.local' => ['es', 'en'],
    ],
    'media_base_url' => [
        'absconsultor.local' => 'http://media.absconsultor.local',
        'absconsultor.es' => 'https://media.absconsultor.es',
        'bravo-silva.ngrok.io' => 'https://bravo-silva.ngrok.io/media'
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
                                'action' => 'home',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'featured-accessories' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/{featured-accessories}',
                            'defaults' => [
                                '__CONTROLLER__' => 'Accessory',
                                'controller' => AccessoryController::class,
                                'action' => 'home',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'accessories' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/{accessories}',
                            'defaults' => [
                                '__CONTROLLER__' => 'Accessory',
                                'controller' => AccessoryController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'category' => [
                                'type'    => 'Segment',
                                'may_terminate' => true,
                                'options' => [
                                    'route' => '/[:category]',
                                    'constraints' => [
                                        'category' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ],
                                    'defaults' => [
                                        '__CONTROLLER__' => 'Accessory',
                                        'controller'    => AccessoryController::class,
                                        'action'        => 'category',
                                    ],
                                ],
                                'child_routes' => [
                                    'detail' => [
                                        'type'    => 'Segment',
                                        'options' => [
                                            'route'    => '/[:detail]',
                                            'constraints' => [
                                                'detail'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                                            ],
                                            'defaults' => [
                                                '__CONTROLLER__' => 'Accessory',
                                                'controller'    => AccessoryController::class,
                                                'action'        => 'detail',
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
                                'controller' => ContactController::class,
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
            'Application\Controller\Accessory' => Controller\AccessoryController::class,
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
            StorageCacheAbstractServiceFactory::class,
            LoggerAbstractServiceFactory::class,
        ],
        'factories' => [
            'Translator' => TranslatorServiceFactory::class,
            SessionService::class => SessionService::class,
            GalleryRenderService::class => InvokableFactory::class
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
            'certificationItemHelper' => CertificationItem::class,
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
            ContactForm::class => InvokableFactory::class,
            CarouselItem::class => CarouselItem::class,
            FacebookShare::class => InvokableFactory::class,
            Translate::class => InvokableFactory::class,
            CertificationItem::class => InvokableFactory::class,
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
                'base_dir' => __DIR__ . '/../languages',
                'pattern' => '%s.php',
                'text_domain' => 'frontend'
            ],
            [
                'type' => 'phpArray',
                'base_dir' => __DIR__ . '/../languages/routing',
                'pattern' => '%s.php',
                'text_domain' => 'routing'
            ],
            [
                'type' => 'phpArray',
                'base_dir' => __DIR__ . '/../languages',
                'pattern' => Resources::getPatternForValidator(),
            ],
        ],
    ],
    'view_manager' => [
        'base_path' => 'https://absconsultor.es/',
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/front-layout.phtml',
            'layout/accessories' => __DIR__ . '/../view/layout/accessories-layout.phtml',
            'layout/pagination-layout' => __DIR__ . '/../view/layout/gallery-pagination-layout.phtml',
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
