<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\View\Http\RouteNotFoundStrategy;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();

        $routeNotFoundStrategy = new RouteNotFoundStrategy();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $routeNotFoundStrategy->attach($eventManager);
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
                'socialIconHelper'      => 'Application\View\Helper\SocialIcon',
                'legalLinkHelper'       => 'Application\View\Helper\LegalLink',
                'megabannerHelper'      => 'Application\View\Helper\Megabanner',
                'homeModuleHelper'      => 'Application\View\Helper\HomeModule',
                'partnerHelper'         => 'Application\View\Helper\Partner',
                'jobHelper'             => 'Application\View\Helper\Job',
                'jobCategoryHelper'     => 'Application\View\Helper\JobCategory',
            ),
        );
    }
}
