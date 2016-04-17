<?php

/**
 * Generated by ZF2ModuleCreator
 */

namespace Administrator;

use Zend\Authentication\AuthenticationService;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

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
                'Administrator\Factory\AdminControllerFactory' => 'Administrator\Factory\AdminControllerFactory',
            ),
            'invokables' => array(

            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'initializers' => array(function ($instance, $sm) {
                //Seteamos el dbAdapter para todos los modelos)
                if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
                    $instance->setServiceLocator($sm);
                    $instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                }
            }),
            'abstract_factories' => array(
                'Administrator\Factory\AdministratorTableAbstractFactory',
                'Administrator\Factory\AdministratorModelAbstractFactory',
            ),
            'factories' => array(
                //'Navigation' => 'Gestor\Factory\GestorNavigationFactory',

                'Administrator\Model\AuthStorage' => function ($sm) {
                    return new \Administrator\Model\AuthStorage('AdministratorStorage');
                },
                'AuthService' => function ($sm) {
                    //My assumption, you've alredy set dbAdapter
                    //and has users table with columns : user_name and pass_word
                    //that password hashed with md5
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $dbTableAuthAdapter  = new AuthAdapter($dbAdapter,
                        'admin_users','email','password', "MD5(?)");

                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('Administrator\Model\AuthStorage'));

                    return $authService;
                },

                'Administrator\Service\SessionServiceInterface' => 'Administrator\Service\SessionService',
                'Administrator\Factory\PermisosCheckerFactory'  => 'Administrator\Factory\PermisosCheckerFactory',
                //'Gestor\Service\GestorFormService'       => 'Gestor\Service\GestorFormService',
                //'Gestor\Service\DatatableService'        => 'Gestor\Service\DatatableService',

            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {

    }
}