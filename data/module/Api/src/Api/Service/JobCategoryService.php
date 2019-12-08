<?php

namespace Api\Service;

use Api\Model\JobCategoryLocaleTable;
use Api\Model\JobCategoryTable;

class JobCategoryService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = JobCategoryTable::class;
    protected $tableLocaleName = JobCategoryLocaleTable::class;

    public function getData()
    {
        return [
            'rows' => $this->table->all(['deleted_at' => null])->setFetchGroupResultSet('id')->toArray(),
            'locale' => $this->tableLocale->findLocales()->setFetchGroupResultSet('languageCode', 'relatedTableId')->toArray()
        ];
    }
}
