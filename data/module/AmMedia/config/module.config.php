<?php

return [
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'service_manager' => [
        'factories' => [
            \AmMedia\FileManager\FileManagerService::class => \AmMedia\FileManager\FileManagerService::class,
            \AmMedia\Service\ScanDirService::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            \AmMedia\Service\InterventionImageService::class => \AmMedia\Service\InterventionImageService::class,
            \AmMedia\Model\MediaModel::class => \Laminas\ServiceManager\Factory\InvokableFactory::class
        ],
    ],
    'AmMedia' => [
        'FileManager' => [
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
            'fmUrl' => '/',

            /**
             * Filemanager plugin to use.
             * Currently available plugins:
             *
             * "s3" - AWS S3 storage plugin (PHP SDK v.3)
             * "rsc" - Rackspace Cloud Files API plugin (most likely obsolete)
             */
            //'plugin'] => 's3'
            'plugin' => null
        ]
    ]
];
