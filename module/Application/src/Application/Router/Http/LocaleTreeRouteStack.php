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

        $webLanguage = 'es_ES';

        $this->serviceLocator = $this->routePluginManager->getServiceLocator();

        $config = $this->serviceLocator->get('Config');

        $routerConfig = $config['router']['frontend_routes_locale'];

        call_user_func_array([$this,'setLocaleRoutes'],array(&$routerConfig,$webLanguage));

        $this->addRoutes($routerConfig);
    }
}