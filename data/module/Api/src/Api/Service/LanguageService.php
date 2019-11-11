<?php

namespace Api\Service;

use Api\Model\LanguageTable;

class LanguageService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = LanguageTable::class;

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