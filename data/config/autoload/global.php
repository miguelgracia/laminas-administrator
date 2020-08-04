<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'db111' => [
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=qwi250;host=qwi250.absconsultor.es',
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ],
        'username' => 'qwi252',
        'password' => 'U4e54b5',
    ],
    'service_manager' => [
        'factories' => [
            'Laminas\Db\Adapter\Adapter' => 'Laminas\Db\Adapter\AdapterServiceFactory',
        ],
    ],
    'js_assets_version' => '1.0.0',
    'css_assets_version' => '1.0.0',
    'admin_js_assets_version' => '1.0.0',
    'admin_css_assets_version' => '1.0.0',
];
