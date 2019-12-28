<?php

namespace Application\Controller;

use Api\Service\AppDataService;
use Api\Service\LanguageService;
use Api\Service\SectionService;
use Api\Service\StaticPageService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Session\Config\ConfigInterface;
use Zend\Session\SessionManager;

abstract class ApplicationController extends AbstractActionController
{
    protected $session;

    protected $lang;

    protected $menu;

    protected $appData;

    protected $headTitleHelper;

    protected $openGraph;

    protected $serviceManager;

    public function onDispatch(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $this->serviceManager = $serviceManager;
        $this->headTitleHelper = $serviceManager->get('ViewHelperManager')->get('headTitle');

        $this->headTitleHelper->setSeparator(' - ');

        $this->headTitleHelper->append('Bravo Silva Consultoría Técnica');

        $cookie = $this->getRequest()->getHeaders()->get('Cookie');

        $showCookieAlert = !isset($cookie->cookie_alert);

        $this->session = $serviceManager->get('frontend');

        $this->openGraph = $serviceManager->get('OpenGraph');

        $ogFacebook = $this->openGraph->facebook();

        $ogFacebook->fb_id = '1593489187619085';
        $ogFacebook->type = 'website';
        $ogFacebook->url = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $_SERVER['REQUEST_URI'];
        $ogFacebook->width = '500';
        $ogFacebook->height = '300';
        $ogFacebook->image = 'http://absconsultor.es/img/white-logo-blue-background.png';

        $routeParams = $this->getEvent()->getRouteMatch()->getParams();

        $this->lang = $this->session->lang;

        $this->menu = $serviceManager->get(SectionService::class)->getMenu();

        $this->appData = $serviceManager->get(AppDataService::class)->getData();

        $languagesForFlags = $serviceManager->get(LanguageService::class)->getLanguagesAvailable([
            'active' => '1',
            'visible' => '1',
        ]);

        $this->layout()->setVariables([
            'showCookieAlert' => $showCookieAlert,
            'languages' => $languagesForFlags,
            'lang' => $this->lang,
            'menu' => $this->menu,
            'appData' => $this->appData,
            'legal' => $serviceManager->get(StaticPageService::class)->getData(),
            'srmController' => 'srm' . $routeParams['__CONTROLLER__'] . 'Controller',
            'controllerAction' => $routeParams['action'] . 'Action',
        ]);

        return parent::onDispatch($e);
    }
}
