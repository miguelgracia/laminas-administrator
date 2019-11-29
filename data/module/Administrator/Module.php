<?php

namespace Administrator;

use Administrator\Factory\AdministratorControllerFactory;
use Administrator\Factory\AdministratorFormServiceFactory;
use Administrator\Factory\AdministratorModelAbstractFactory;
use Administrator\Factory\AdministratorTableAbstractFactory;
use Administrator\Factory\DatatableConfigAbstractFactory;
use Administrator\Initializer\DatabaseInitializer;
use Administrator\Model\AuthStorage;
use Administrator\Service\AdministratorFormService;
use Administrator\Service\AuthService;
use Administrator\Service\DatatableService;
use Administrator\Service\SessionService;
use Administrator\View\Helper\AdministratorFormRow;
use Administrator\View\Helper\AdministratorMenu;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    protected $mvcEvent;

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // Autoload all classes from namespace 'Administrator' from '/module/Administrator/src/Administrator'
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                )
            )
        );
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                AdministratorControllerFactory::class => AdministratorControllerFactory::class,
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'initializers' => array(
                DatabaseInitializer::class,
            ),
            'abstract_factories' => array(
                AdministratorTableAbstractFactory::class,
                AdministratorModelAbstractFactory::class,
                DatatableConfigAbstractFactory::class,
            ),
            'factories' => array(

                'AuthService' => AuthService::class,
                SessionService::class => SessionService::class,
                AdministratorFormService::class => AdministratorFormServiceFactory::class,
                DatatableService::class => DatatableService::class,
            ),
            'invokables' => array(
                AuthStorage::class => AuthStorage::class,
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'AdministratorMenu' => AdministratorMenu::class,
            ),
            'factories' => array(
                'administrator_form_row' => function () {
                    return new AdministratorFormRow;
                }
            )
        );
    }
}