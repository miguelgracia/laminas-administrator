<?php

namespace Application\View\Http;


use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class RouteNotFoundStrategy  extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {
        $events->attach(MvcEvent::EVENT_DISPATCH_ERROR,[$this, 'onDispatchError'],100);
    }

    public function onDispatchError(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        $api = $serviceManager->get('Application\Api');

        $session = $serviceManager->get('Application\Service\SessionService');

        $viewModel = $event->getViewModel();

        $viewModel ->setVariables([
            'lang'             => $session->lang,
            'menu'             => $api->section->getMenu(),
            'appData'          => $api->appData->getData(),
            'srmController'    => 'srmErrorController',
            'controllerAction' => 'indexAction',
        ]);
    }
}