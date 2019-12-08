<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'factories' => [
            \AmModule\Form\ModuleForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmModule\Form\ModuleFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'service_manager' => array(
        'factories' => array(
            'AmModule\Service\ModuleService' => 'AmModule\Service\ModuleService'
        )
    ),
);