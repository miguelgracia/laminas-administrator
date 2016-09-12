<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

abstract class ApplicationController extends AbstractActionController
{
    protected $session;

    protected $api;

    protected $lang;

    protected $menu;

    protected $appData;

    protected $headTitleHelper;

    public function onDispatch(MvcEvent $e)
    {
        $this->headTitleHelper = $e->getApplication()->getServiceManager()->get('ViewHelperManager')->get('headTitle');

        $this->headTitleHelper->setSeparator(' - ');

        $this->headTitleHelper->append('ABS Consultor');

        $cookie = $this->getRequest()->getHeaders()->get('Cookie');

        $showCookieAlert = !isset($cookie->cookie_alert);

        $this->session = $this->serviceLocator->get('Application\Service\SessionService');

        $this->api = $this->serviceLocator->get('Application\Api');

        $routeParams = $this->getEvent()->getRouteMatch()->getParams();

        $this->lang = $this->session->lang;

        $this->menu = $this->api->section->getMenu();

        $this->appData = $this->api->appData->getData();

        $this->layout()->setVariables([
            'showCookieAlert'  => $showCookieAlert,
            'lang'             => $this->lang,
            'menu'             => $this->menu,
            'appData'          => $this->appData,
            'legal'            => $this->api->staticPage->getData(),
            'srmController'    => 'srm'.$routeParams['__CONTROLLER__'].'Controller',
            'controllerAction' => $routeParams['action'].'Action',
        ]);

        return parent::onDispatch($e);
    }
}