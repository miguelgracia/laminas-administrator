<?php

namespace Api\Service;

use Api\Model\LanguageTable;
use Zend\ServiceManager\FactoryInterface;

class LanguageService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = LanguageTable::class;


    public function getLanguagesAvailable()
    {
        return $this->table->all(array(
            'active' => '1',
            'visible' => '1',
        ),array(
            'order ASC'
        ));
    }
}