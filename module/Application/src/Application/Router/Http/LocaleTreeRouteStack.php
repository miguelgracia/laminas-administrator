<?php

namespace Application\Router\Http;

use Zend\Mvc\Router\Http\TranslatorAwareTreeRouteStack;

class LocaleTreeRouteStack extends TranslatorAwareTreeRouteStack
{
    protected $serviceLocator;

    private function setLocaleRoutes (&$route, $webLanguage) {

        foreach ($route as &$routeConfig) {

            if (isset($routeConfig['child_routes'])) {
                call_user_func_array([$this,'setLocaleRoutes'],array(&$routeConfig['child_routes'],$webLanguage));
            }

            if (is_array($routeConfig['options']['route'])) {
                $routeConfig['options']['route'] = $routeConfig['options']['route'][$webLanguage];
            }
        }
    }

    public function init()
    {
        parent::init();

        $this->serviceLocator = $this->routePluginManager->getServiceLocator();

        $config = $this->serviceLocator->get('Config');

        $session = $this->serviceLocator->get('Application\Service\SessionService');

        if ($session->lang == null) {

            $helperServerUrl = $this->serviceLocator->get('ViewHelperManager');

            $serverUrl = $helperServerUrl->get('serverUrl');

            //quitamos las www (si existen) y el puerto (si existe)
            $host = preg_replace("/(www\.)|(:\d+$)/", "", $serverUrl->getHost());

            $session->lang = array_key_exists($host, $config['languages_by_host'])
                ? $config['languages_by_host'][$host]
                : $config['default_language'];

        }

        $config = $this->serviceLocator->get('Config');

        $routerConfig = $config['router']['frontend_routes_locale'];

        call_user_func_array([$this,'setLocaleRoutes'],array(&$routerConfig,$session->lang));

        $this->addRoutes($routerConfig);
    }
}