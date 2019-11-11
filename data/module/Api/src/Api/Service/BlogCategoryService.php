<?php

namespace Api\Service;

use Api\Model\BlogCategoryLocaleTable;
use Api\Model\BlogCategoryTable;

class BlogCategoryService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = BlogCategoryTable::class;
    protected $tableLocaleName = BlogCategoryLocaleTable::class;

    public function getData()
    {
        return array(
            'rows' => $this->table->all(array('deleted_at' => null))->setFetchGroupResultSet('id')->toArray(),
            'locale' => $this->tableLocale->findLocales()->setFetchGroupResultSet('languageCode','relatedTableId')->toArray()
        );
    }
}