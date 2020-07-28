<?php

namespace Application\Controller;

use Api\Service\AccessoryService;
use Api\Service\CertificationService;
use Api\Service\ContactService;
use Api\Service\JobService;
use Api\Service\PartnerService;
use Api\Service\StaticPageService;
use Application\Form\ContactFieldset;
use Laminas\Db\Adapter\Adapter;
use Laminas\Stdlib\Parameters;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class HomeController extends ApplicationController
{
    private $captchaSecret = '6LdGVMwUAAAAAGak-tvRIV77Q2NYlGWskB4t5tPB';

    protected $form;
    protected $messages;

    public function indexAction()
    {
        $menuLang = $this->menu->locale->{$this->lang};
        $menuLangHome = $menuLang[$this->menu->rows->home->id];

        //$this->headTitleHelper->append('Home');

        $ogFacebook = $this->openGraph->facebook();
        $ogFacebook->title = $this->headTitleHelper->renderTitle();
        $ogFacebook->description = $menuLangHome->metaDescription;

        $this->layout()->setVariables([
            'og' => $ogFacebook,
        ]);

        return new ViewModel(
            $this->layout()->getVariables()->getArrayCopy()
            + $this->getIntros()
            + $this->getForms()
            + $this->getGalleries()
            + $this->getText()
            + $this->getCertifications()
        );
    }

    private function getCertifications()
    {
        return [
            'certifications' => $this->serviceManager->get(CertificationService::class)->getData(),
        ];
    }

    private function getGalleries()
    {
        return [
            'accessoriesUrl' => $this->url()->fromRoute('locale/accessories'),
            'jobUrl' => $this->url()->fromRoute('locale/jobs', ['locale' => $this->lang, 'type' => 'jobs'], ['query' => ['page' => 0]]),
            'partners' => $this->serviceManager->get(PartnerService::class)->getData(),
            'jobs' => $this->serviceManager->get(JobService::class)->getJobs($this->lang, true),
            'accessories' => $this->serviceManager->get(AccessoryService::class)->getAccessories($this->lang, true),
        ];
    }

    private function getText()
    {
        return [
            'legal' => $this->serviceManager->get(StaticPageService::class)->getData(),
        ];
    }

    private function getIntros()
    {
        $menuLang = $this->menu->locale->{$this->lang};

        return [
            'contactIntro' => $menuLang[$this->menu->rows->contact->id]->content,
            'accessoriesIntro' => $menuLang[$this->menu->rows->accessories->id]->content,
            'companyIntro' => $menuLang[$this->menu->rows->company->id]->content,
            'servicesIntro' => $menuLang[$this->menu->rows->services->id]->content,
            'workIntro' => $menuLang[$this->menu->rows->jobs->id]->content,
        ];
    }

    private function getForms()
    {
        $contactService = $this->serviceManager->get(ContactService::class);

        $options = [
            'captcha_secret' => false
        ];

        return [
            'formActionUrl' => $this->url()->fromRoute('locale/contact', ['locale' => $this->lang]),
            'contactForm' => $contactService->createForm(new ContactFieldset('contact', $options, $this->serviceManager->get(Adapter::class))),
        ];
    }
}
