<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmPartner\Controller\AmPartnerModuleController' => 'AmPartner\Controller\AmPartnerModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);