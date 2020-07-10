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
        self::IS_BOT   => "The user must be a bot",
    ];

    public function isValid($token)
    {
        $secret = $this->getOption('captcha_secret');

        $captchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$token";

        $result = json_decode(file_get_contents($captchaUrl));

        if ($result->success) {
            return true;
        }

        $this->error(self::IS_BOT);
        return false;
    }

}