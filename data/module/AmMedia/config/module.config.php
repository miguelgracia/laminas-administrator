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
        'factories' => array(
            'AmMedia\FileManager\FileManagerService' => 'AmMedia\FileManager\FileManagerService'
        ),
        'aliases' => array(
            "plupload_adapter" => 'Zend\Db\Adapter\Adapter'
        )
    ),

    'router' => array(

    ),
    'AmMedia' => array(
        'FileManager' => array(
            /**
             * Path to the Filemanager folder.
             * Set path in case the PHP connector class was moved or extended.
             * If you use the default Filemanager files structure it will be defined automatically as ancestor of the PHP connector class.
             * @var string|null
             */

            'fmPath' => $_SERVER['DOCUMENT_ROOT'] .
                DIRECTORY_SEPARATOR . 'administrator' .
                DIRECTORY_SEPARATOR . 'libs' .
                DIRECTORY_SEPARATOR . 'RichFilemanager' .
                DIRECTORY_SEPARATOR . 'scripts',

            /**
             * Relative path to the Filemanager which is accessible via URL.
             * Define if url to Filemanager is different from its path. Some cases:
             * - use of custom rules for URL routing
             * - use of "dynamic" folders, like when "publishing" assets from secured location
             * @var string|null
             */
            'fmUrl' => "/",

            /**
             * Filemanager plugin to use.
             * Currently available plugins:
             *
             * "s3" - AWS S3 storage plugin (PHP SDK v.3)
             * "rsc" - Rackspace Cloud Files API plugin (most likely obsolete)
             */
            //'plugin'] => 's3'
            'plugin' => null
        ),
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