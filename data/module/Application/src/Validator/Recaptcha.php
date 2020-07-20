<?php

namespace Application\Validator;

use Laminas\Validator\AbstractValidator;

class Recaptcha extends AbstractValidator
{
    const IS_BOT = 'isBot';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = [
        self::IS_BOT => "The user must be a bot",
    ];

    public function isValid($token)
    {
        $secret = $this->getOption('captcha_secret');

        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $data = [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        $curlConfig = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data
        );

        $ch = curl_init();

        curl_setopt_array($ch, $curlConfig);
        $response = curl_exec($ch);
        curl_close($ch);

        $jsonResponse = json_decode($response);

        if ($jsonResponse->success) {
            return true;
        }

        $this->error(self::IS_BOT);
        return false;
    }

}