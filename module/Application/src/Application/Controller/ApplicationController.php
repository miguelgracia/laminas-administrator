<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

abstract class ApplicationController extends AbstractActionController
{
    protected $session;

    protected $api;

    public function onDispatch(MvcEvent $e)
    {
        $this->session = $this->serviceLocator->get('Application\Service\SessionService');

        $this->api = $this->serviceLocator->get('Application\Api');

        $routeParams = $this->getEvent()->getRouteMatch()->getParams();

        $this->layout()->setVariables([
            'lang'             => $routeParams['lang'],
            'appData'          => $this->api->appData->getData(),
            'srmController'    => 'srm'.$routeParams['__CONTROLLER__'].'Controller',
            'controllerAction' => $routeParams['action'].'Action',
        ]);

        return parent::onDispatch($e);
    }
}