<?php

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'form_elements' => [
        'factories' => [
            \AmBlog\Form\Element\BlogCategoriesId::class => \Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
            \AmBlog\Form\BlogForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmBlog\Form\BlogFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmBlog\Form\BlogLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class,
        ]
    ],
);