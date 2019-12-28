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
use Api\Service\PartnerService;
use Zend\View\Model\ViewModel;

class HomeController extends ApplicationController
{
    public function indexAction()
    {
        $menuLang = $this->menu->locale->{$this->lang};

        $menuLangHome = $menuLang[$this->menu->rows->home->id];

        $this->headTitleHelper->append('Home');

        $ogFacebook = $this->openGraph->facebook();
        $ogFacebook->title = $this->headTitleHelper->renderTitle();
        $ogFacebook->description = $menuLangHome->metaDescription;

        $this->layout()->setVariables([
            'og' => $ogFacebook,
        ]);

        $vars = [
                'partners' => $this->serviceManager->get(PartnerService::class)->getData($this->lang)
            ] + $this->layout()->getVariables()->getArrayCopy();


        return new ViewModel($vars);
    }
}
