<?php

namespace AmTool;

use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module implements ConsoleUsageProviderInterface, AutoloaderProviderInterface, ConfigProviderInterface
{
    const NAME = 'AmTool - Zend Framework 2 command line Tool';

    /**
     * @var ServiceLocatorInterface
     */
    protected $sm;

    public function onBootstrap(EventInterface $e)
    {
        $this->sm = $e->getApplication()->getServiceManager();
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }

    public function getConsoleBanner(ConsoleAdapterInterface $console)
    {
        return self::NAME;
    }

    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {
        $config = $this->sm->get('config');
        if (!empty($config['AmTool']) && !empty($config['AmTool']['disable_usage'])) {
            return null; // usage information has been disabled
        }

        // TODO: Load strings from a translation container
        return [
            'Basic information:',
            'modules [list]' => 'show loaded modules',
            'version | --version' => 'display current Zend Framework version',

            'Diagnostics',
            'diag [options] [module name]' => 'run diagnostics',
            ['[module name]', '(Optional) name of module to test'],
            ['-v --verbose', 'Display detailed information.'],
            ['-b --break', 'Stop testing on first failure'],
            ['-q --quiet', 'Do not display any output unless an error occurs.'],
            ['--debug', 'Display raw debug info from tests.'],

            'Application configuration:',
            'config list' => 'list all configuration options',
            'config get <name>' => 'display a single config value, i.e. "config get db.host"',
            'config set <name> <value>' => 'set a single config value (use only to change scalar values)',

            'Project creation:',
            'create project <path>' => 'create a skeleton application',
            ['<path>', 'The path of the project to be created'],

            'Module creation:',
            'create module <name> [<path>]' => 'create a module',
            ['<name>', 'The name of the module to be created'],
            ['<path>', 'The root path of a ZF2 application where to create the module'],

            'Controller creation:',
            'create controller <name> <module> [<path>]' => 'create a controller in module',
            ['<name>', 'The name of the controller to be created'],
            ['<module>', 'The module in which the controller should be created'],
            ['<path>', 'The root path of a ZF2 application where to create the controller'],

            'Action creation:',
            'create action <name> <controllerName> <module> [<path>]' => 'create an action in a controller',
            ['<name>', 'The name of the action to be created'],
            ['<controllerName>', 'The name of the controller in which the action should be created'],
            ['<module>', 'The module containing the controller'],
            ['<path>', 'The root path of a ZF2 application where to create the action'],

            'Classmap generator:',
            'classmap generate <directory> <classmap file> [--append|-a] [--overwrite|-w]' => '',
            ['<directory>',        'The directory to scan for PHP classes (use "." to use current directory)'],
            ['<classmap file>',    'File name for generated class map file  or - for standard output. ' .
                                        'If not supplied, defaults to autoload_classmap.php inside <directory>.'],
            ['--append | -a',      'Append to classmap file if it exists'],
            ['--overwrite | -w',   'Whether or not to overwrite existing classmap file'],

            'Zend Framework 2 installation:',
            'install zf <path> [<version>]' => '',
            ['<path>', 'The directory where to install the ZF2 library'],
            ['<version>', 'The version to install, if not specified uses the last available'],
        ];
    }
}
