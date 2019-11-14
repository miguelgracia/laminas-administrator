<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'factories' => [
            \AmAppData\Form\AppDataForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmAppData\Form\AppDataFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
            \AmAppData\Form\AppDataLocaleFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class
        ]
    ],

    'service_manager' => array(

    ),

    'router' => array(

    )
);