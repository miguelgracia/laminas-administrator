<?php

namespace Application\Service;

interface SessionServiceInterface
{
    /**
     * Creates a session with the Zend session thing
     */
    public function initSession(array $config);
}
