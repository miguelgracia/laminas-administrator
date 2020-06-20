<?php

namespace Application\Controller;

use Api\Service\AccessoryService;
use Api\Service\ContactService;
use Api\Service\JobService;
use Api\Service\PartnerService;
use Api\Service\StaticPageService;
use Application\Form\ContactFieldset;
use Application\Form\QuestionFieldset;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class HomeController extends ApplicationController
{
    private $captchaSecret = '6LdGVMwUAAAAAGak-tvRIV77Q2NYlGWskB4t5tPB';

    protected $form;
    protected $messages;

    private function validation($fieldset)
    {
        $contactService = $this->serviceManager->get(ContactService::class);

        $this->form = $contactService
            ->createForm($fieldset)
            ->setData($this->request->getPost());

        $isValid = $this->form->isValid();

        if (!$isValid) {
            $messages = $this->form->get($fieldset->getName())->getMessages();

            foreach ($messages as &$message) {
                foreach ($message as &$msg) {
                    $msg = $this->translator->translate($msg, 'default', $this->lang);
                }
            }

            $this->messages = $messages;
        };

        return $isValid;
    }

    public function questionAction()
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

        if (!$this->validation(new QuestionFieldset('question', [
            'captcha_secret' => $this->captchaSecret
        ],$this->serviceManager->get(Adapter::class)))) {
            $this->getResponse()->setStatusCode(422);

            return new JsonModel([
                'status' => 'ko',
                'error' => true,
                'message' => $this->messages
            ]);
        }

        $mailSended = $contactService->sendFormMail($this->form->getData(), $this->appData->row->questionMailInbox, 'question');

        return new JsonModel([
            'status' => 'ok',
            'error' => false,
            'message' => $this->translator->translate(
                $mailSended ? 'Mensaje enviado' : 'Mensaje NO enviado',
                'default',
                $this->lang
            ),
        ]);
    }

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

        if (!$this->validation(new ContactFieldset('contact', [
            'captcha_secret' => $this->captchaSecret
        ]))) {
            $this->getResponse()->setStatusCode(422);

            return new JsonModel([
                'status' => 'ko',
                'error' => true,
                'message' => $this->messages
            ]);
        }

        $mailSended = $contactService->sendFormMail($this->form->getData(), $this->appData->row->mailInbox);

        return new JsonModel([
            'status' => 'ok',
            'error' => false,
            'message' => $this->translator->translate(
                $mailSended ? 'Mensaje enviado' : 'Mensaje NO enviado',
                'default',
                $this->lang
            ),
        ]);
    }

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
        );
    }

    private function getGalleries()
    {
        $jobService = $this->serviceManager->get(JobService::class);
        $accessoryService = $this->serviceManager->get(AccessoryService::class);

        return [
            'accessoriesUrl' => $this->url()->fromRoute('locale/accessories', ['locale' => $this->lang, 'type' => 'accessories'], ['query' => ['page' => 1]]),
            'jobUrl' => $this->url()->fromRoute('locale/jobs', ['locale' => $this->lang, 'type' => 'jobs'], ['query' => ['page' => 1]]),
            'partners' => $this->serviceManager->get(PartnerService::class)->getData($this->lang),
            'jobs' => $jobService->getJobs($this->lang, true),
            'accessories' => $accessoryService->getAccessories($this->lang, true),
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
            'questionIntro' => $menuLang[$this->menu->rows->technicalquestion->id]->content,
            'companyIntro' => $menuLang[$this->menu->rows->company->id]->content,
            'servicesIntro' => $menuLang[$this->menu->rows->services->id]->content,
            'workIntro' => $menuLang[$this->menu->rows->jobs->id]->content,
        ];
    }

    private function getForms()
    {
        $contactService = $this->serviceManager->get(ContactService::class);
        $questionService = clone $contactService;

        $options = [
            'captcha_secret' => false
        ];

        return [
            'formActionUrl' => $this->url()->fromRoute('locale/contact', ['locale' => $this->lang]),
            'contactForm' => $contactService->createForm(new ContactFieldset('contact', $options)),
            'questionForm' => $questionService->createForm(new QuestionFieldset('question', $options, $this->serviceManager->get(Adapter::class))),
        ];
    }
}
