<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class ApplicationController extends AbstractActionController
{

    protected $session;

    public function onDispatch(MvcEvent $e)
    {
        $this->session = $this->serviceLocator->get('Application\Service\SessionService');

        return parent::onDispatch($e);
    }

}