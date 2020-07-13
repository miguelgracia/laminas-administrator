<?php

namespace Application\Controller;

use Api\Service\AccessoryService;
use Api\Service\ContactService;
use Api\Service\JobService;
use Api\Service\PartnerService;
use Api\Service\StaticPageService;
use Application\Form\ContactFieldset;
use Application\Form\QuestionFieldset;
use Laminas\Db\Adapter\Adapter;
use Laminas\Stdlib\Parameters;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class HomeController extends ApplicationController
{
    private $captchaSecret = '6LdGVMwUAAAAAGak-tvRIV77Q2NYlGWskB4t5tPB';

    protected $form;
    protected $messages;

    private function validation($fieldset)
    {
        $contactService = $this->serviceManager->get(ContactService::class);

        $parameters = new Parameters;

        $parameters->fromArray(
            array_merge_recursive(
                $this->request->getPost()->toArray(),
                $this->request->getFiles()->toArray()
            )
        );

        $this->form = $contactService
            ->createForm($fieldset)
            ->setData($parameters);

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
        ], $this->serviceManager->get(Adapter::class)))) {
            $this->getResponse()->setStatusCode(422);

            return new JsonModel([
                'status' => 'ko',
                'error' => true,
                'message' => $this->messages
            ]);
        }

        $post = $this->request->getPost();

        $mailInbox = $post['question_code'] !== ''
            ? $this->appData->row->mailTechnicalInbox
            : $this->appData->row->mailInbox;

        $mailSended = $contactService->sendFormMail($this->form->getData(), $mailInbox);

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
        return [
            'accessoriesUrl' => $this->url()->fromRoute('locale/accessories'),
            'jobUrl' => $this->url()->fromRoute('locale/jobs', ['locale' => $this->lang, 'type' => 'jobs'], ['query' => ['page' => 0]]),
            'partners' => $this->serviceManager->get(PartnerService::class)->getData($this->lang),
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
        $questionService = clone $contactService;

        $options = [
            'captcha_secret' => false
        ];

        return [
            'formActionUrl' => $this->url()->fromRoute('locale/contact', ['locale' => $this->lang]),
            'contactForm' => $contactService->createForm(new ContactFieldset('contact', $options, $this->serviceManager->get(Adapter::class))),
            'questionForm' => $questionService->createForm(new QuestionFieldset('question', $options, $this->serviceManager->get(Adapter::class))),
        ];
    }
}
