<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'aliases' => [
            'adminModuleId' => \AmMenu\Form\Element\AdminModuleId::class,
            'parent' => \Zend\Form\Element\Hidden::class,
            'order' => \Zend\Form\Element\Hidden::class,
            'action' => \AmMenu\Form\Element\Action::class,
        ],
        'factories' => [
            \AmMenu\Form\MenuForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmMenu\Form\MenuFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'service_manager' => array(
        'factories' => array(
            'Navigation' => 'AmMenu\Factory\MenuNavigationFactory',
        )
    ),
);