<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmBlog\Controller\AmBlogModuleController' => 'AmBlog\Controller\AmBlogModuleController'
        )
    ),

    'service_manager' => array(

    ),

    'router' => array(

    )
);