<?php

namespace OpenGraph\Service;

class OpenGraphService
{
    protected $facebook;

    public function __construct()
    {
        $this->facebook = new \stdClass();
    }

    public function facebook()
    {
        return $this->facebook;
    }
}
