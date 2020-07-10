<?php

return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'aliases' => [
            // TODO: Lo suyo seria quiza que el alias realmente sea un objecto de tipo Element
            'AmMenu\\Form\\Element\\Parent' => \Laminas\Form\Element\Hidden::class,
            'AmMenu\\Form\\Element\\Order' => \Laminas\Form\Element\Hidden::class,
        ],
        'factories' => [
            \AmMenu\Form\Element\Action::class => \Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
            \AmMenu\Form\Element\AdminModuleId::class => \Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
            \AmMenu\Form\MenuForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmMenu\Form\MenuFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ],
        'delegators' => [
            \AmMenu\Form\MenuFieldset::class => [
                \AmMenu\Form\Factory\MenuFieldsetDelegatorFactory::class
            ]
        ]
    ],

    'service_manager' => [
        'factories' => [
            'Navigation' => \AmMenu\Factory\MenuNavigationFactory::class,
        ]
    ],
];
