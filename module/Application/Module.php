<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function (MvcEvent $e) {

            $serviceManager = $e->getApplication()->getServiceManager();

            $api = $serviceManager->get('Application\Api');

            $session = $serviceManager->get('Application\Service\SessionService');

            $viewModel = $e->getViewModel();

            $viewModel ->setVariables([
                'lang'             => $session->lang,
                'menu'             => $api->section->getMenu(),
                'appData'          => $api->appData->getData(),
                'srmController'    => 'srmErrorController',
                'controllerAction' => 'indexAction',
            ]);

        },100);
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'applicationMenuHelper' => 'Application\View\Helper\Menu',
            ),
        );
    }
}
