<?php

use Administrator\Factory\AdministratorControllerFactory;
use Administrator\Factory\AdministratorFormServiceFactory;
use Administrator\Factory\AdministratorModelAbstractFactory;
use Administrator\Factory\AdministratorTableAbstractFactory;
use Administrator\Model\AuthStorage;
use Administrator\Service\AdministratorFormService;
use Administrator\Service\DatatableService;
use Administrator\Service\SessionService;
use Laminas\I18n\Translator\Resources;

return [
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/admin-layout' => __DIR__ . '/../view/layout/admin-layout.phtml',
            'layout/admin-login-layout' => __DIR__ . '/../view/layout/admin-login-layout.phtml',
            'error/index' => __DIR__ . '/../view/layout/error.phtml',
            //'error/404'               => __DIR__ . '/../view/error/404.phtml',
            //'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'translator' => [
        'locale' => 'es',
        'translation_file_patterns' => [
            /*[
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ],*/
            [
                'type' => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php',
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'administrator' => [
                'type' => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route' => '/admin[/[:module[/[:action[/[:id]]]]]]',
                    'defaults' => [
                        'controller' => AdministratorControllerFactory::class,
                        'action' => 'index'
                    ]
                ]
            ]
        ],
    ],

    'controllers' => [
        'factories' => [
            AdministratorControllerFactory::class => AdministratorControllerFactory::class,
        ],
    ],
    'datatable' => [
        'factories' => [
            \AmAppData\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
            \AmJob\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
            \AmJobCategory\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
            \AmLanguage\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
            \AmModule\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
            \AmPartner\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
            \AmProfile\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
            \AmSection\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
            \AmStaticPage\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
            \AmUser\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
            \AmAccessory\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
            \AmAccessoryCategory\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class
        ]
    ],
    'form_elements' => [
        /**
         * Conforme vayan surgiendo posibles elementos por defecto en función del tipo de dato, el siguiente array
         * ira creciendo
         */
        'aliases' => [
            /**
             * Nombres comunes en las tablas declaradas
             */
            'active' => \Administrator\Form\Element\YesNoSelect::class,
            'visible' => \Administrator\Form\Element\YesNoSelect::class,
            'targetLink' => \Administrator\Form\Element\TargetLink::class,
            'metaDescription' => \Administrator\Form\Element\SimpleTextarea::class,
            'content' => \Laminas\Form\Element\Textarea::class,
            'password' => \Laminas\Form\Element\Password::class,
            'imageUrl' => \Administrator\Form\Element\ImageUrl::class,

            /**
             * Si no se especifica el nombre del campo en base de datos, resolvemos un objeto
             * Element en función del tipo de dato seteado en mysql
             */
            'int' => \Laminas\Form\Element\Text::class,
            'varchar' => \Laminas\Form\Element\Text::class,
            'char' => \Laminas\Form\Element\Text::class,
            'tinyint' => \Laminas\Form\Element\Text::class,
            'enum' => \Laminas\Form\Element\Select::class,
            'timestamp' => \Laminas\Form\Element\DateSelect::class,
            'tinytext' => \Laminas\Form\Element\Textarea::class,
            'mediumtext' => \Laminas\Form\Element\Textarea::class,
            'longtext' => \Laminas\Form\Element\Textarea::class,
        ],
        'factories' => [
            \Administrator\Form\Element\YesNoSelect::class => \Laminas\Form\ElementFactory::class,
            \Administrator\Form\Element\TargetLink::class => \Laminas\Form\ElementFactory::class,
            \Administrator\Form\Element\SimpleTextarea::class => \Laminas\Form\ElementFactory::class,
            \Administrator\Form\Element\ImageUrl::class => \Administrator\Form\Element\ImageUrlFactory::class
        ],
    ],
    'service_manager' => [
        'initializers' => [
            \Administrator\Initializer\DatabaseInitializer::class,
        ],
        'aliases' => [
            'DatatablePluginManager' => \Administrator\Service\DatatablePluginManager::class,
        ],
        'abstract_factories' => [
            AdministratorTableAbstractFactory::class,
            AdministratorModelAbstractFactory::class,
        ],
        'factories' => [
            AuthStorage::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            \Administrator\Service\ConfigureFieldsetService::class => \Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory::class,
            \Administrator\Service\CheckIdService::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            'AuthService' => \Administrator\Factory\AuthFactory::class,
            SessionService::class => SessionService::class,
            AdministratorFormService::class => AdministratorFormServiceFactory::class,
            \Administrator\Service\DatatablePluginManager::class => \Administrator\Factory\DatatablePluginManagerFactory::class,
            DatatableService::class => \Administrator\Factory\DatatableFactory::class
        ]
    ],
    'view_helpers' => [
        'aliases' => [
            'AdministratorMenu' => \Administrator\View\Helper\AdministratorMenu::class,
        ],
        'factories' => [
            \Administrator\View\Helper\AdministratorMenu::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            'administrator_form_row' => function () {
                return new \Administrator\View\Helper\AdministratorFormRow;
            }
        ]
    ],
    \Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory::class => [
        \Administrator\Service\ConfigureFieldsetService::class => [
            'FormElementManager',
            \Administrator\Service\CheckIdService::class
        ]
    ]
];
