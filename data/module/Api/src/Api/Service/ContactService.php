<?php

namespace Api\Service;

use Laminas\Form\Form;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Sendmail;
use Laminas\Mail\Transport\Smtp;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\Validator\EmailAddress;
use Laminas\Mime\Message as MimeMessage;
use Laminas\Mime\Mime;
use Laminas\Mime\Part as MimePart;

class ContactService
{
    /**
     * @var \Laminas\Form\Form
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

    private function parseFiles(&$files)
    {
        foreach ($files as $key => $file) {
            if ($file['size'] === 0) {
                unset($files[$key]);
            }
        }
    }

    public function sendFormMail($formData, $mailTo, $fieldset = 'contact')
    {
        $ignoreFields = ['question_legal', 'legal', 'g-recaptcha-response', 'file'];

        $mailValidator = new EmailAddress();

        if (!$mailValidator->isValid($mailTo)) {
            return false;
        }

        $mail = (new Message)
            ->setFrom('absconsultor@absconsultor.es', "ABS Consultor - Contacto Web")
            ->setEncoding('UTF-8')
            ->addTo($mailTo, 'ABS Consultor')
            ->setSubject('Información de contacto desde la web');

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

        $formDataText = '';

        foreach ($formData[$fieldset] as $field => $fieldValue) {
            if (in_array($field, $ignoreFields)) {
                continue;
            }
            $formDataText .= $translations[$field] . ': ' . $fieldValue . "[END_OF_LINE]";
        }

        $files = $formData[$fieldset]['file'];

        $this->parseFiles($files);

        if (count($files)) {
            $formDataText = str_replace('[END_OF_LINE]', '<br>', $formDataText);
            $html = new MimePart($formDataText);
            $html->type = Mime::TYPE_HTML;
            $html->charset = 'utf-8';
            $html->encoding = Mime::ENCODING_QUOTEDPRINTABLE;

            $parts = [$html];

            foreach ($files as $file) {
                $image = new MimePart(fopen($file['tmp_name'], 'r'));
                $image->type = $file['type'];
                $image->filename = $file['name'];
                $image->disposition = Mime::DISPOSITION_ATTACHMENT;
                $image->encoding = Mime::ENCODING_BASE64;

                $parts[] = $image;
            }

            $body = new MimeMessage();
            $body->setParts($parts);

            $mail->setBody($body);

            $contentTypeHeader = $mail->getHeaders()->get('Content-Type');
            $contentTypeHeader->setType('multipart/related');
        } else {
            $formDataText = str_replace('[END_OF_LINE]', "\n", $formDataText);
            $body = $formDataText;
            $mail->setBody($body);
        }

        /*$transport = new Smtp();
        $options   = new SmtpOptions([
            'name'              => 'smtp.gmail.com',
            'host'              => 'smtp.gmail.com',
            'port'              => 587,
            'connection_class'  => 'plain',
            'connection_config' => [
                'username' => '[GMAIL]',
                'password' => '[PASSWORD GMAIL]',
                'ssl' => 'tls'
            ],
        ]);

        $transport->setOptions($options);
        $transport->send($mail);*/

        (new Sendmail())->send($mail);

        return true;
    }
}
