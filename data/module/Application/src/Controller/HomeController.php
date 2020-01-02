<?php

namespace Application\Controller;

use Api\Service\AccessoryService;
use Api\Service\JobService;
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
            'contactIntro' => $menuLang[$this->menu->rows->contact->id]->content,
            'accessoriesIntro' => $menuLang[$this->menu->rows->accessories->id]->content,
            'questionIntro' => $menuLang[$this->menu->rows->technicalquestion->id]->content,
            'companyIntro' => $menuLang[$this->menu->rows->company->id]->content,
            'servicesIntro' => $menuLang[$this->menu->rows->services->id]->content,
            'workIntro' => $menuLang[$this->menu->rows->work->id]->content,
            'partners' => $this->serviceManager->get(PartnerService::class)->getData($this->lang),
            'featuredJobs' => $this->serviceManager->get(JobService::class)->getFeaturedJobs($this->lang),
            'featuredAccessories' => $this->serviceManager->get(AccessoryService::class)->getFeaturedAccessories($this->lang),
        ];

        return new ViewModel($this->layout()->getVariables()->getArrayCopy() + $vars);
    }
}
