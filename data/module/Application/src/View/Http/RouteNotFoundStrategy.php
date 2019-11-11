<?php

namespace Application\View\Http;


use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class RouteNotFoundStrategy  extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $events->attach(MvcEvent::EVENT_DISPATCH_ERROR,[$this, 'onDispatchError'],$priority);
    }

    public function onDispatchError(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        $api = $serviceManager->get('Application\Api');

        $session = $serviceManager->get('Application\Service\SessionService');

        $viewModel = $event->getViewModel();

        $cookie = $event->getRequest()->getHeaders()->get('Cookie');

        $showCookieAlert = !isset($cookie->cookie_alert);

        $viewModel ->setVariables([
            'showCookieAlert'  => $showCookieAlert,
            'lang'             => $session->lang,
            'menu'             => $api->section->getMenu(),
            'appData'          => $api->appData->getData(),
            'legal'            => $api->staticPage->getData(),
            'srmController'    => 'srmErrorController',
            'controllerAction' => 'indexAction',
        ]);
    }
}