<?php

namespace Api\Service;

use Api\Model\AccessoryCategoryLocaleTable;
use Api\Model\AccessoryCategoryTable;

class AccessoryCategoryService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = AccessoryCategoryTable::class;
    protected $tableLocaleName = AccessoryCategoryLocaleTable::class;

    public function getData()
    {
        return [
            'rows' => $this->table->all(['deleted_at' => null])->setFetchGroupResultSet('id')->toArray(),
            'locale' => $this->tableLocale->findLocales()->setFetchGroupResultSet('languageCode', 'relatedTableId')->toArray()
        ];
    }
}
