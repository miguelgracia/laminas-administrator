<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'form_elements' => [
        'factories' => [
            \AmPartner\Form\PartnerForm::class => \Administrator\Factory\AdministratorFormFactory::class,
            \AmPartner\Form\PartnerFieldset::class => \Administrator\Factory\AdministratorFieldsetFactory::class,
        ]
    ],

    'service_manager' => array(

    ),

    'router' => array(

    )
);