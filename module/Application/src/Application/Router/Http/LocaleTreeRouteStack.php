<?php

namespace Application\Router\Http;

use Zend\Mvc\Router\Http\TreeRouteStack;

class LocaleTreeRouteStack extends TreeRouteStack
{
    protected $serviceLocator;

    private function setLocaleRoutes (&$route, $webLanguage) {

        foreach ($route as &$routeConfig) {

            if (isset($routeConfig['child_routes'])) {
                call_user_func_array([$this,'setLocaleRoutes'],array(&$routeConfig['child_routes'],$webLanguage));
            }

            if (isset($routeConfig['options']['constraints'])) {
                foreach ($routeConfig['options']['constraints'] as &$constraint) {
                    if (is_array($constraint)) {
                        $constraint = $constraint[$webLanguage];
                    }
                }
            }
        }
    }

    protected function init()
    {
        parent::init();

        $this->serviceLocator = $this->routePluginManager->getServiceLocator();

        $request = $this->serviceLocator->get('Request');
        $uri = $request->getUri();

        $config = $this->serviceLocator->get('Config');

        $session = $this->serviceLocator->get('Application\Service\SessionService');

        $host = $uri->getHost();

        $hostLanguages = $config['languages_by_host'][$host];

        //comprobamos que en la url tenemos el segmento (y solo ese segmento) de idioma. Ninguno más.
        preg_match("/^\/((\w{2})_(\w{2}))\/*$/", $uri->getPath(), $langArray);


        if (count($langArray) > 0 and preg_grep("/".$langArray[1]."/i",$hostLanguages)) {
            $currentLang = $langArray[2] . '_' . strtoupper($langArray[2]);
        } else {
            $currentLang = $hostLanguages[0];
        }

        $session->lang = $currentLang;

        $langChildRoutes = array();

        foreach ($hostLanguages as $lang) {
            if (!isset($langChildRoutes[$lang])) {
                $auxRouterConfig = $config['router']['frontend_routes_locale'];
                call_user_func_array([$this,'setLocaleRoutes'],array(&$auxRouterConfig,$lang));
                $langChildRoutes[$lang] = $auxRouterConfig['lang'];
            }
        }

        $config['router']['home']['options']['defaults']['lang'] = $currentLang;

        $routerConfig = $langChildRoutes + array('home' => $config['router']['home']);

        $this->addRoutes($routerConfig);
    }
}