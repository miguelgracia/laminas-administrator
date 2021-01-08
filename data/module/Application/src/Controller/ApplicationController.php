<?php

namespace Application\Controller;

use Api\Service\AppDataService;
use Api\Service\LanguageService;
use Api\Service\SectionService;
use Api\Service\StaticPageService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\Config\ConfigInterface;
use Laminas\Session\SessionManager;

abstract class ApplicationController extends AbstractActionController
{
    protected $session;

    protected $lang;

    protected $menu;

    protected $appData;

    protected $headTitleHelper;

    protected $openGraph;

    protected $serviceManager;

    protected $translator;

    public function onDispatch(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $this->translator = $serviceManager->get('MvcTranslator');

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
        $ogFacebook->url = 'https://' . $_SERVER['SERVER_NAME'] . '/' . $_SERVER['REQUEST_URI'];
        $ogFacebook->width = '500';
        $ogFacebook->height = '300';
        $ogFacebook->image = 'http://laminas-admin.local/img/white-logo-blue-background.jpg';

        $routeParams = $this->getEvent()->getRouteMatch()->getParams();

        $this->lang = $this->session->lang;

        $this->menu = $serviceManager->get(SectionService::class)->getMenu();

        $this->appData = $serviceManager->get(AppDataService::class)->getData();

        $languagesForFlags = $serviceManager->get(LanguageService::class)->getLanguagesAvailable([
            'active' => '1',
            'visible' => '1',
        ]);

        $config = $serviceManager->get('Config');


        $this->layout()->setVariables([
            'showCookieAlert' => $showCookieAlert,
            'languages' => $languagesForFlags,
            'lang' => $this->lang,
            'menu' => $this->menu,
            'appData' => $this->appData,
            'legal' => $serviceManager->get(StaticPageService::class)->getData(),
            'srmController' => 'srm' . $routeParams['__CONTROLLER__'] . 'Controller',
            'controllerAction' => $routeParams['action'] . 'Action',
            'jsVersion' => $config['js_assets_version'],
            'cssVersion' => $config['css_assets_version'],
        ]);

        return parent::onDispatch($e);
    }
}
