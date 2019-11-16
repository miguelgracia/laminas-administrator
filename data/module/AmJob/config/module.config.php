<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'factories' => [
            \AmJob\Form\JobForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmJob\Form\JobFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmJob\Form\JobLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],

    'service_manager' => array(

    ),

    'router' => array(

    )
);