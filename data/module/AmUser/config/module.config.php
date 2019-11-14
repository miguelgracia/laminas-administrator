<?php

/**
 * Generated by ZF2ModuleCreator
 */

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'factories' => [
            \AmUser\Form\AmUserForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmUser\Form\UserFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class
        ]
    ],

    'service_manager' => array(

    ),

    'router' => array(

    )
);