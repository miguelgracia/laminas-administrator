<?php

/**
 *  Filemanager default configuration file
 *
 *  Use to override or extend configuration of filemanager.config.default.json
 *  You can define some secure config data to keep it safe.
 *
 *  @license     MIT License
 *  @author      Pavel Solomienko <https://github.com/servocoder/>
 *  @copyright   Authors
 */

$ds = DIRECTORY_SEPARATOR;

return array(
    /**
     * Path to the Filemanager folder.
     * Set path in case the PHP connector class was moved or extended.
     * If you use the default Filemanager files structure it will be defined automatically as ancestor of the PHP connector class.
     * @var string|null
     */

    'fmPath' => $_SERVER['DOCUMENT_ROOT'] . $ds . 'administrator' . $ds . 'libs' . $ds . 'RichFilemanager' . $ds . 'scripts',

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
);