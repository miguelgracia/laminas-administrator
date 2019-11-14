<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'factories' => [
            \AmMegabanner\Form\MegabannerForm::class=> \Administrator\Factory\AdministratorFormFactory::class,
            \AmMegabanner\Form\MegabannerFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'service_manager' => array(

    ),

    'router' => array(

    )
);