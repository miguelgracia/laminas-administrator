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
 *
 * Configuraci�n global
 */

return array(
    // Configuraci�n de la base de datos
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=abs_consultor;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        // ******************************* IMPORTANTE ****************************
        // Esto deber�a sobreescribirse con un valor en config/autoload/local.php si se desea una configuracion local
        'username' => 'root',
        'password' => '',
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),

    /*'module_layouts' => array(
        'Application'   => 'layout/front-layout',
        'Administrator' => 'layout/layout',
    ),*/




);
