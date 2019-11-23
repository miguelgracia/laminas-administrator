<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'aliases' => [
            'isAdmin' => \Administrator\Form\Element\YesNoSelect::class,
            'description' => \Zend\Form\Element\Textarea::class,
        ],
        'factories' => [
            \AmProfile\Form\Element\Permissions::class => \Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
            \AmProfile\Form\ProfileForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmProfile\Form\ProfileFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'service_manager' => array(

        'factories' => array(
            'AmProfile\Service\ProfilePermissionService'  => 'AmProfile\Service\ProfilePermissionService',
        )
    ),

    'router' => array(

    )
);