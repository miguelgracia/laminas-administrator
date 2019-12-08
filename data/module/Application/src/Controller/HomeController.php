<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Api\Service\HomeModuleService;
use Api\Service\MegabannerService;
use Zend\View\Model\ViewModel;

class HomeController extends ApplicationController
{
    public function indexAction()
    {
        $menu = $this->menu;
        $menuLang = $menu->locale->{$this->lang};

        $menuLangHome = $menuLang[$menu->rows->home->id];

        $this->headTitleHelper->append('Home');

        $ogFacebook = $this->openGraph->facebook();
        $ogFacebook->title = $this->headTitleHelper->renderTitle();
        $ogFacebook->description = $menuLangHome->metaDescription;

        $this->layout()->setVariable('og', $ogFacebook);

        return new ViewModel([
            'homeModules' => $this->serviceManager->get(HomeModuleService::class)->getData($this->lang),
            'megabanners' => $this->serviceManager->get(MegabannerService::class)->getData($this->lang),
            'lang' => $this->lang,
            'menu' => $this->menu
        ]);
    }
}
