<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'factories' => [
            \AmStaticPage\Form\StaticPageForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmStaticPage\Form\StaticPageFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmStaticPage\Form\StaticPageLocaleFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class
        ]
    ],

    'service_manager' => array(

    ),

    'router' => array(

    )
);