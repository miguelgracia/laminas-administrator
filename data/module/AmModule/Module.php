<?php

namespace AmModule;

use AmModule\Service\ModuleService;
use Autoload\ModuleConfigTrait;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\MvcEvent;

class Module
{
    use ModuleConfigTrait;

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $translator = $serviceManager->get(Translator::class);

        $locale = $translator->getLocale();

        //Cargamos las traducciones de los m�dulos del administrador
        $adminModules = $serviceManager->get(ModuleService::class)->getModules();

        foreach ($adminModules as $module) {
            $translateFile = __DIR__ . "/../$module/language/$locale/locale.php";
            $translateFile = str_replace('/', DIRECTORY_SEPARATOR, $translateFile);
            if (is_file($translateFile)) {
                $translator->getTranslator()->addTranslationFile(
                    'phpArray',
                    $translateFile,
                    'default',
                    $locale
                );
            }
        }
    }
}
