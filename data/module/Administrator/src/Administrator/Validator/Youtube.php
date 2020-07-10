<?php

namespace Administrator\Validator;

use Laminas\Validator\Exception;
use Laminas\Validator\Regex;

class Youtube extends Regex
{
    public function __construct()
    {
        $pattern = "/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/";
        parent::__construct($pattern);
    }
}
