<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'factories' => [
            \AmUser\Form\Element\AdminProfileId::class => \Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
            \AmUser\Form\AmUserForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmUser\Form\UserFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class
        ]
    ],
);