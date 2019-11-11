<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Factory\ApplicationHelperFactory;
use Application\View\Helper\Blog;
use Application\View\Helper\BlogCategory;
use Application\View\Helper\CarouselItem;
use Application\View\Helper\ContactForm;
use Application\View\Helper\FacebookShare;
use Application\View\Helper\HomeModule;
use Application\View\Helper\Job;
use Application\View\Helper\JobCategory;
use Application\View\Helper\LegalLink;
use Application\View\Helper\Megabanner;
use Application\View\Helper\Menu;
use Application\View\Helper\Partner;
use Application\View\Helper\SocialIcon;
use Zend\I18n\View\Helper\Translate;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Session\SessionManager;

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
        preg_match("/^\/((\w{2})_(\w{2}))\/*/", $uri->getPath(), $langArray);

        if (count($langArray) > 0 and preg_grep("/".$langArray[1]."/i",$hostLanguages)) {
            $currentLang = $langArray[2] . '_' . $langArray[2];
        } else {
            $currentLang = $hostLanguages[0];
        }

        $session->lang = $currentLang;

        $serviceManager->get('MvcTranslator')->setLocale($currentLang);
    }

    public function getViewHelperConfig()
    {
        return array(
            'aliases' => array(
                'applicationMenuHelper' => Menu::class,
                'socialIconHelper'      => SocialIcon::class,
                'legalLinkHelper'       => LegalLink::class,
                'megabannerHelper'      => Megabanner::class,
                'homeModuleHelper'      => HomeModule::class,
                'partnerHelper'         => Partner::class,
                'jobHelper'             => Job::class,
                'jobCategoryHelper'     => JobCategory::class,
                'blogHelper'            => Blog::class,
                'blogCategoryHelper'    => BlogCategory::class,
                'contactFormHelper'     => ContactForm::class,
                'carouselItemHelper'    => CarouselItem::class,
                'facebookShareHelper'   => FacebookShare::class,
            ),
            'factories' => [
                Menu::class => ApplicationHelperFactory::class,
                SocialIcon::class => InvokableFactory::class,
                LegalLink::class => InvokableFactory::class,
                Megabanner::class => InvokableFactory::class,
                HomeModule::class => InvokableFactory::class,
                Partner::class => Partner::class,
                Job::class => InvokableFactory::class,
                JobCategory::class => InvokableFactory::class,
                Blog::class => InvokableFactory::class,
                BlogCategory::class => InvokableFactory::class,
                ContactForm::class => ContactForm::class,
                CarouselItem::class => CarouselItem::class,
                FacebookShare::class => InvokableFactory::class
            ],
            'invokables' => [
                'translate' => Translate::class
            ]
        );
    }
}
