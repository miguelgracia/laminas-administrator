<?php

namespace Application\Controller;

use Api\Service\ContactService;
use Application\Form\ContactFieldset;
use Laminas\Db\Adapter\Adapter;
use Laminas\Stdlib\Parameters;
use Laminas\View\Model\JsonModel;

class ContactController extends ApplicationController
{

    private $captchaSecret = '6LdGVMwUAAAAAGak-tvRIV77Q2NYlGWskB4t5tPB';

    protected $form;
    protected $messages;

    private function validation($fieldset)
    {
        $contactService = $this->serviceManager->get(ContactService::class);

        $parameters = new Parameters;

        $files = $this->request->getFiles()->toArray();

        if ($files['contact']['file'][0]['error'] !== 0) {
            $files = [];
        }

        $parameters->fromArray(
            array_merge_recursive(
                $this->request->getPost()->toArray(),
                $files
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
}
