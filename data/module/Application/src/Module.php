<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Laminas\Mvc\MvcEvent;
use Laminas\Session\SessionManager;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $serviceManager->get(SessionManager::class)->start();

        $request = $serviceManager->get('Request');
        $uri = $request->getUri();

        $config = $serviceManager->get('Config');

        $session = $serviceManager->get('frontend');

        $host = $uri->getHost();

        $hostLanguages = $config['languages_by_host'][$host];

        //comprobamos que en la url tenemos el segmento de idioma.
        preg_match("/^\/((\w{2}))\/*/", $uri->getPath(), $langArray);

        if (count($langArray) > 0 and preg_grep('/' . $langArray[1] . '/i', $hostLanguages)) {
            $currentLang = $langArray[2];
        } else {
            $currentLang = $hostLanguages[0];
        }

        $session->lang = $currentLang;

        $serviceManager->get('MvcTranslator')->setLocale($currentLang);
    }
}
