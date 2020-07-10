<?php

namespace Api\Service;

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

    public function createForm($fieldset)
    {
        return (new Form)
            ->add($fieldset)
            ->setAttributes([
                'id' => $fieldset->getName() . '_form',
                'class' => 'form-horizontal',
                'method' => 'POST'
            ]);
    }

    public function sendFormMail($formData, $mailTo, $fieldset = 'contact')
    {
        $ignoreFields = ['question_legal', 'legal', 'g-recaptcha-response'];

        $mailValidator = new EmailAddress();

        if (!$mailValidator->isValid($mailTo)) {
            return false;
        }

        $translations = [
            'phone' => 'Teléfono',
            'question_name' => 'Nombre',
            'name' => 'Nombre',
            'question_email' => 'Email',
            'email' => 'Email',
            'question_topic' => 'Tipo de pregunta',
            'question_code' => 'Codigo cliente',
            'question_message' => 'Mensaje',
            'message' => 'Mensaje',
        ];

        $body = '';

        foreach ($formData[$fieldset] as $field => $fieldValue) {
            if (in_array($field, $ignoreFields)) {
                continue;
            }
            $body .= $translations[$field] . ': ' . $fieldValue . "\n";
        }

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
