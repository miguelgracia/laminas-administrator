<?php

namespace Api\Service;

use Application\Form\ContactFieldset;
use Zend\Form\Form;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\ServiceManager\FactoryInterface;
use Zend\Validator\EmailAddress;

class ContactService implements FactoryInterface
{
    use ApiServiceTrait;

    /**
     * @var \Zend\Form\Form
     */
    protected $form;

    public function createForm()
    {
        $fieldset = new ContactFieldset('contact', array());
        $this->form = new Form();
        $this->form->add($fieldset);
        $this->form->setAttribute('method', 'post');
        return $this->form;
    }

    public function bindForm($postData)
    {
        $this->form->setData($postData);
    }

    public function validateForm()
    {
        return $this->form->isValid();
    }

    public function sendFormMail($mailTo)
    {
        $mailValidator = new EmailAddress();

        if ($mailValidator->isValid($mailTo)) {
            $formData = $this->form->getData();

            $formData = $formData['contact'];

            $mail = new Message();
            $mail->setFrom('absconsultor@absconsultor.es', "ABS Consultor - Contacto Web");
            $mail->addTo($mailTo, 'ABS Consultor');
            $mail->setSubject('Información de contacto desde la web');

            $body  = 'Nombre   : ' . $formData['name'] . "\n";
            $body .= 'Email    : ' . $formData['email'] . "\n";
            $body .= 'Teléfono : ' . $formData['phone'] . "\n";
            $body .= 'Mensaje  : ' . $formData['message'] . "\n";

            $mail->setBody($body);

            $transport = new Sendmail();
            $transport->send($mail);

            return true;
        }
        return false;
    }
}