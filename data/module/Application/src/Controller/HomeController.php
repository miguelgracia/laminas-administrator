<?php

namespace Application\Controller;

use Api\Service\AccessoryService;
use Api\Service\ContactService;
use Api\Service\JobService;
use Api\Service\PartnerService;
use Api\Service\StaticPageService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class HomeController extends ApplicationController
{
    private $captchaSecret = '6LdGVMwUAAAAAGak-tvRIV77Q2NYlGWskB4t5tPB';

    public function contactAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(
                [
                    'isAjax' => $this->getRequest()->isXmlHttpRequest(),
                    'isPost' => $this->getRequest()->isPost()
                ]
            );
        }

        $contactService = $this->serviceManager->get(ContactService::class);

        $form = $contactService
            ->createForm($this->captchaSecret)
            ->setData($this->request->getPost());

        if ($form->isValid()) {
            $mailTo = $this->appData->row->mailInbox;
            $mailSended = $contactService->sendFormMail($mailTo);
            $vars = [
                'status' => 'ok',
                'error' => false,
                'message' => $mailSended ? 'Mensaje enviado' : 'Mensaje NO enviado',
            ];
        } else {
            $this->getResponse()->setStatusCode(422);
            $vars = [
                'status' => 'ko',
                'error' => true,
                'message' => $form->get('contact')->getMessages()
            ];
        }

        return new JsonModel($vars);
    }

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

        $contactService = $this->serviceManager->get(ContactService::class);

        $vars = [
            'formActionUrl' => $this->url()->fromRoute('locale/contact', ['locale' => $this->lang]),
            'contactForm' => $contactService->createForm(),
            'contactIntro' => $menuLang[$this->menu->rows->contact->id]->content,
            'legal' => $this->serviceManager->get(StaticPageService::class)->getData(),
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
