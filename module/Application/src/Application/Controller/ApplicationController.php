<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

abstract class ApplicationController extends AbstractActionController
{
    protected $session;

    public function onDispatch(MvcEvent $e)
    {
        $this->session = $this->serviceLocator->get('Application\Service\SessionService');

        $routeParams = $this->getEvent()->getRouteMatch()->getParams();

        $this->layout()->setVariables([
            'srmController' => 'srm'.$routeParams['__CONTROLLER__'].'Controller',
            'controllerAction' => $routeParams['action'].'Action',
        ]);

        return parent::onDispatch($e);
    }

}