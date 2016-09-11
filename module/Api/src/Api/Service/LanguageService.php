<?php

namespace Api\Service;

use Api\Model\LanguageTable;
use Zend\ServiceManager\FactoryInterface;

class LanguageService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = LanguageTable::class;
}