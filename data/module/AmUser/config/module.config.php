<?php

return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'form_elements' => [
        'factories' => [
            \AmUser\Form\Element\AdminProfileId::class => \Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
            \AmUser\Form\AmUserForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmUser\Form\UserFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class
        ]
    ],
    'datatable' => [
        'factories' => [
            \AmUser\Service\DatatableConfigService::class => \Administrator\Factory\DatatableConfigFactory::class,
        ]
    ],
];
