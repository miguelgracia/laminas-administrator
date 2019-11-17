<?php

return array(

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/admin-layout'       => __DIR__ . '/../view/layout/admin-layout.phtml',
            'layout/admin-login-layout' => __DIR__ . '/../view/layout/admin-login-layout.phtml',
            'error/index'               => __DIR__ . '/../view/layout/error.phtml',
            //'error/404'               => __DIR__ . '/../view/error/404.phtml',
            //'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
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
    'router' => array(

        'routes' => array(
            'administrator' => array(
                'type' => \Zend\Router\Http\Segment::class,
                'options' => array(
                    'route' => '/admin[/[:module[/[:action[/[:id]]]]]]',
                    'defaults' => array(
                        'controller' => \Administrator\Factory\AdministratorControllerFactory::class,
                        'action' => 'index'
                    )
                )
            )
        ),
    ),
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
            'metaDescription' => \Administrator\Form\Element\MetaDescription::class,
            'content' => \Zend\Form\Element\Textarea::class,
            'password' => \Zend\Form\Element\Password::class,

            /**
             * Si no se especifica el nombre del campo en base de datos, resolvemos un objeto
             * Element en función del tipo de dato seteado en mysql
             */
            'int' => \Zend\Form\Element\Text::class,
            'varchar' => \Zend\Form\Element\Text::class,
            'tinyint' => \Zend\Form\Element\Text::class,
            'enum' => \Zend\Form\Element\Select::class,
            'timestamp' => \Zend\Form\Element\DateSelect::class
        ],

        'form_elements' => [
            'factories' => [
                \Administrator\Form\Element\YesNoSelect::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
                \Administrator\Form\Element\TargetLink::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
                \Administrator\Form\Element\MetaDescription::class => \Zend\ServiceManager\Factory\InvokableFactory::class
            ],
        ],
        'abstract_factories' => [
            \Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory::class,
            \Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
        ]
    ],
);