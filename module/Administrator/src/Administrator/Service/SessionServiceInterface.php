<?php

namespace Administrator\Service;

interface SessionServiceInterface
{

    /**
     * Creates a session with the Zend session thing
     */
    public function initSession(Array $config);

}