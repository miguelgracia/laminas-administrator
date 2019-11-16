<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'factories' => [
            \AmSection\Form\SectionForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmSection\Form\SectionFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmSection\Form\SectionLocaleFieldset::class => \Administrator\Factory\AdministratorLocaleFieldsetFactory::class
        ]
    ],

    'service_manager' => array(

    ),

    'router' => array(

    )
);