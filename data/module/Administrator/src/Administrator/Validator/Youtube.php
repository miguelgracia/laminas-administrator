<?php
namespace Administrator\Validator;

use Zend\Validator\Exception;
use Zend\Validator\Regex;

class Youtube extends Regex
{
    public function __construct()
    {
        $pattern = "/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/";
        parent::__construct($pattern);
    }
}