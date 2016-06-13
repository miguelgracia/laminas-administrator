<?php

return array(

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AmMedia\Controller\AmMediaModuleController' => 'AmMedia\Controller\AmMediaModuleController'
        )
    ),

    'service_manager' => array(
        'aliases' => array(
            "plupload_adapter" => 'Zend\Db\Adapter\Adapter'
        )
    ),

    'router' => array(

    ),
    'AmMedia' => array(
        'tableName'          => 'media',
        'UrlUpload'          => '/quplupload/upload',
        'UrlRemove'          => '/quplupload/remove',
        'UrlLoad'            => '/quplupload/load',
        'DirUpload'          => '/uploads/files/plupload',
        'DirUploadAbsolute'  =>  dirname(dirname(dirname(__DIR__)))  . '/public/media',
        'DirJs'              => 'js/plugins/plupload',
        'Resize'             => array(
            'blog' => array(
                'xl' => array('1170','420'),
                'l'  => array('600','550'),
                'm'  => array('500','418'),
                's'  => array('30','20'),
            ),
        ),
        'ThumbResize'        => array(

        ),
    )
);