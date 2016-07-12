<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmLanguage\Controller\AmLanguageModuleController' => 'AmLanguage\Controller\AmLanguageModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);