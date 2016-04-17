<?php

namespace Gestor;

use Gestor\Listener\CrudListener;
use Gestor\View\Helper\GestorFormRow;
use Zend\Authentication\AuthenticationService;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getRouteConfig()
    {

    }

    /**
     * Mapeamos e inicializamos los controladores
     * @return array
     */
    public function getControllerConfig()
    {
        return array(

            'invokables' => array(
                'Gestor\Controller\Login'             => 'Gestor\Controller\LoginController',
                'Gestor\Controller\Configuration'     => 'Gestor\Controller\ConfigurationController',
                'Gestor\Controller\Home'              => 'Gestor\Controller\HomeController',
                'Gestor\Controller\Perfil'            => 'Gestor\Controller\PerfilController',
                'Gestor\Controller\GestorUsuarios'    => 'Gestor\Controller\GestorUsuariosController',
                'Gestor\Controller\EntradaMenu'       => 'Gestor\Controller\EntradaMenuController',
                'Gestor\Controller\GestorControlador' => 'Gestor\Controller\GestorControladorController',
                'Gestor\Controller\Permisos'          => 'Gestor\Controller\PermisosController',

                'Gestor\Controller\Coordinador'       => 'Gestor\Controller\CoordinadorController',
                'Gestor\Controller\DirectorRelacion'  => 'Gestor\Controller\DirectorRelacionController',
                'Gestor\Controller\Especialistas'     => 'Gestor\Controller\EspecialistasController',
                'Gestor\Controller\Equipos'           => 'Gestor\Controller\EquiposController',
                'Gestor\Controller\Estadisticas'      => 'Gestor\Controller\EstadisticasController',
                'Gestor\Controller\Presentacion'      => 'Gestor\Controller\PresentacionController',

                'Gestor\Controller\Oficina'      => 'Gestor\Controller\OficinaController',
                'Gestor\Controller\Guiadeuso'      => 'Gestor\Controller\GuiadeusoController',
                'Gestor\Controller\Importaciones'      => 'Gestor\Controller\ImportacionesController',
                'Gestor\Controller\MisDatos'      => 'Gestor\Controller\MisDatosController',

            ),
            'factories' => array(
                'Gestor\Factory\AdminControllerFactory' => 'Gestor\Factory\AdminControllerFactory',
            ),
        );
    }

    // Este es el método del ServiceConfig donde se meten las factorías para el acceso al modelo/tablas
    // y cualquier otra clase que declaremos como servicio
    public function getServiceConfig()
    {
        return array(
            'initializers' => array(
                function ($instance, $sm) {
                    //Seteamos el dbAdapter para todos los modelos)
                    if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
                        $instance->setServiceLocator($sm);
                        $instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                    }
                }
            ),
            'factories' => array(
                'Navigation' => 'Gestor\Factory\GestorNavigationFactory',

                'Gestor\Model\AuthStorage' => function ($sm) {
                    return new \Gestor\Model\AuthStorage('gestorstorage');
                },
                'AuthService' => function ($sm) {
                    //My assumption, you've alredy set dbAdapter
                    //and has users table with columns : user_name and pass_word
                    //that password hashed with md5
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $config = $sm->get('Config');

                    $encryptKey = $config['encrypt_key'];
                    $dbTableAuthAdapter  = new AuthAdapter($dbAdapter,
                        'gestorusuarios','login','password', "AES_ENCRYPT(?,'$encryptKey')");

                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('Gestor\Model\AuthStorage'));

                    return $authService;
                },

                'Gestor\Service\SessionServiceInterface' => 'Gestor\Service\SessionService',
                'Gestor\Factory\PermisosCheckerFactory'  => 'Gestor\Factory\PermisosCheckerFactory',
                'Gestor\Service\GestorFormService'       => 'Gestor\Service\GestorFormService',
                'Gestor\Service\DatatableService'        => 'Gestor\Service\DatatableService',
                'PdfService'                             => 'Gestor\Service\PdfService',

            ),

            'shared' => array(
                'Gestor\Service\GestorFormService' => false
            ),

            'abstract_factories' => array(
                'Gestor\Factory\GestorModelAbstractFactory',
                'Gestor\Factory\GestorTableAbstractFactory',
            ),

            'invokables' => array(

                'Gestor\Service\ImageUploadService'       => 'Gestor\Service\ImageUploadService',
                'Gestor\Service\FileUploadService'       => 'Gestor\Service\FileUploadService',
                /**
                 * Modelos

                'Gestor\Model\ValoresConfiguracionTable' => 'Gestor\Model\ValoresConfiguracionTable',
                'Gestor\Model\PerfilTable'               => 'Gestor\Model\PerfilTable',
                'Gestor\Model\GestorUsuariosTable'       => 'Gestor\Model\GestorUsuariosTable',
                'Gestor\Model\PermisosTable'             => 'Gestor\Model\PermisosTable',
                'Gestor\Model\GestorControladorTable'    => 'Gestor\Model\GestorControladorTable',
                'Gestor\Model\EntradaMenuTable'          => 'Gestor\Model\EntradaMenuTable',
                'Gestor\Model\BlogTable'                 => 'Gestor\Model\BlogTable',
                'Gestor\Model\BlogInfoTable'             => 'Gestor\Model\BlogInfoTable',*/
            ),

        );
    }

    // Esta es la configuración de los helpers
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'GestorMenu' => 'Gestor\View\Helper\GestorMenu',
                'DRPestanya' => 'Gestor\View\Helper\DRPestanya',
            ),
            'factories' => array(

                'settings' => function ($sm) {
                    return new \Gestor\View\Helper\AppSettingsHelper();
                },
                'menu_helper' => function($sm) {
                    return new \Gestor\View\Helper\MenuHelper($sm);
                },
                'gestor_user' => function($sm) {
                    return new \Gestor\View\Helper\GestorUserHelper($sm);
                },
                'gestor_form_row' => function ($sm) {
                    return new GestorFormRow($sm);
                }

            )
        );
    }

    // Puedes meter un metodo que se ejecute antes que el resto de las cosas
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getTarget()->getEventManager();
        $eventManager->attach(new CrudListener());
    }
}