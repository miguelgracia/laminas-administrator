<?php

namespace Application\Controller;

use Api\Service\ContactService;
use Api\Service\StaticPageService;
use Zend\View\Model\ViewModel;

class ContactController extends ApplicationController
{
    public function indexAction()
    {
        $prg = $this->prg($this->url()->fromRoute('locale/contact', ['locale' => $this->lang]),true);

        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            // Returned a response to redirect us.
            return $prg;
        }

        $form = $this->serviceManager->get(ContactService::class)->createForm();

        $menu = $this->menu;

        $menuLang = $this->menu->locale->{$this->lang};

        $menuLangContact = $menuLang[$menu->rows->contact->id];

        $this->headTitleHelper->append($menuLang[$this->menu->rows->contact->id]->name);

        $ogFacebook = $this->openGraph->facebook();
        $ogFacebook->title = $this->headTitleHelper->renderTitle();
        $ogFacebook->description = $menuLangContact->metaDescription;

        $this->layout()->setVariable('og',$ogFacebook);

        $mailSended = null;

        if ($prg === false) {

            if (!isset($menu->rows->contact) or $menu->rows->contact->active == 0) {
                $this->getResponse()->setStatusCode(404);
            }
        } else {

            $contactService = $this->serviceManager->get(ContactService::class);
            $contactService->bindForm($prg);

            $isValid = $contactService->validateForm();

            if ($isValid) {
                $mailTo = $this->appData->row->mailInbox;
                $mailSended = $contactService->sendFormMail($mailTo);
            }
        }

        return new ViewModel(array(
            'formObject'     => $form,
            'legal'          => $this->serviceManager->get(StaticPageService::class)->getData(),
            'menu'           => $this->menu,
            'lang'           => $this->lang,
            'appData'        => $this->appData,
            'mailSended'     => $mailSended,
        ));
    }
}
