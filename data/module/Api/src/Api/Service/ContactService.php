<?php

namespace Api\Service;

use Application\Form\ContactFieldset;
use Zend\Form\Form;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Validator\EmailAddress;

class ContactService
{
    /**
     * @var \Zend\Form\Form
     */
    protected $form;

    public function createForm($captchaSecret = false)
    {
        $this->form = (new Form)
            ->add(new ContactFieldset('contact', [
                'captcha_secret' => $captchaSecret
            ]))
            ->setAttributes([
                'id' => 'contact_form',
                'class' => 'form-horizontal',
                'method' => 'POST'
            ]);

        return $this->form;
    }

    public function sendFormMail($mailTo)
    {
        $mailValidator = new EmailAddress();

        if (!$mailValidator->isValid($mailTo)) {
            return false;
        }

        $formData = $this->form->get('contact');
        $body = sprintf(
            "Nombre   : %s \n
                 Email    : %s \n
                 Teléfono : %s \n
                 Mensaje  : %s \n",
            $formData->get('name')->getValue(),
            $formData->get('email')->getValue(),
            $formData->get('phone')->getValue(),
            $formData->get('message')->getValue());

        $mailTo = "miguelgraciamartin@gmail.com";

        $mail = (new Message)
            ->setFrom('absconsultor@absconsultor.es', "ABS Consultor - Contacto Web")
            ->setEncoding('UTF-8')
            ->addTo($mailTo, 'ABS Consultor')
            ->setSubject('Información de contacto desde la web')
            ->setBody($body);

        (new Sendmail())->send($mail);

        return true;
    }
}
