<?php

namespace AmMedia;

use AmMedia\Entity\PluploadHydrator;
use AmMedia\Listener\MediaListener;
use AmMedia\View\Helper\PluploadHelp;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'plupload_entity'   => 'AmMedia\Entity\PluploadEntity',
            ),
            'factories' => array(
                'plupload_service'  => 'AmMedia\Service\PluploadService',
                'intervention_image' => 'AmMedia\Service\InterventionImageService',
                'plupload_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\PluploadOptions(
                        isset($config['QuConfig']['AmMedia']) ? $config['QuConfig']['AmMedia'] : array()
                    );
                },
                'plupload_model' => function ($sm) {
                    $options = $sm->get('plupload_options');
                    $PluploadModel = new Model\PluploadModel();
                    $PluploadModel->setUploadDir($options->getDirUploadAbsolute());
                    return $PluploadModel;
                },
                'plupload_mapper' => function ($sm) {
                    $options = $sm->get('plupload_options');
                    $mapper  = new Entity\PluploadMapper();
                    $mapper->setTableName($options->getTableName());
                    $mapper->setDbAdapter($sm->get('plupload_adapter'));
                    $mapper->setEntityPrototype($sm->get('plupload_entity'));
                    $mapper->setHydrator(new PluploadHydrator());
                    return $mapper;
                },
                'thumb_model' => function ($sm) {

                    $options = $sm->get('plupload_options');
                    $ThumbModel = new Model\ThumbModel();
                    $ThumbModel->setUploadDir($options->getDirUploadAbsolute());
                    $ThumbModel->setThumbResize($options->getThumbResize());
                    $ThumbModel->setThumbService($sm->get('intervention_image'));
                    return $ThumbModel;
                },
                'remove_model' => function ($sm) {
                    $options = $sm->get('plupload_options');
                    $RemoveModel = new Model\RemoveModel();
                    $RemoveModel->setUploadDir($options->getDirUploadAbsolute());
                    $RemoveModel->setThumbResize($options->getThumbResize());
                    return $RemoveModel;
                },
            ),
        );
    }
    /**
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'PluploadHelp' => function ($sm) {
                    $config = $sm->getServiceLocator()->get('config');
                    return new PluploadHelp(
                        isset($config['QuConfig']['AmMedia']) ? $config['QuConfig']['AmMedia'] : array()
                    );
                },
                'PluploadHelpLoad' => function ($sm) {
                    $sm = $sm->getServiceLocator();
                    $plupload_service = $sm->get('plupload_service');
                    $config = $sm->get('config');
                    return new PluploadHelpLoad($plupload_service,
                        isset($config['QuConfig']['AmMedia']) ? $config['QuConfig']['AmMedia'] : array()
                    );
                },
            ),
        );
    }

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
                    // Autoload all classes from namespace 'AmController' from '/module/AmController/src/AmController'
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                )
            )
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager  = $e->getApplication()->getEventManager();
        $eventManager->attach(new MediaListener());
    }
}