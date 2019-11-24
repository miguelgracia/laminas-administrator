<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'aliases' => [
            // TODO: Lo suyo seria quiza que el alias realmente sea un objecto de tipo Element
            'AmMenu\\Form\\Element\\Parent' => \Zend\Form\Element\Hidden::class,
            'AmMenu\\Form\\Element\\Order' => \Zend\Form\Element\Hidden::class,
        ],
        'factories' => [
            \AmMenu\Form\Element\Action::class => \Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
            \AmMenu\Form\Element\AdminModuleId::class => \Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
            \AmMenu\Form\MenuForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmMenu\Form\MenuFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'service_manager' => array(
        'factories' => array(
            'Navigation' => \AmMenu\Factory\MenuNavigationFactory::class,
        )
    ),
);