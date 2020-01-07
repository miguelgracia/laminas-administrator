<?php

namespace Api\Service;

use Api\Model\StaticPageLocaleTable;
use Api\Model\StaticPageTable;

class StaticPageService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = StaticPageTable::class;
    protected $tableLocaleName = StaticPageLocaleTable::class;

    protected $cacheData = [];

    public function getData()
    {
        if (array_key_exists(__FUNCTION__, $this->cacheData)) {
            return $this->cacheData[__FUNCTION__];
        }

        $result = [
            'rows' => $this->table->all([
                'active' => '1',
                'deleted_at' => null
            ])->setFetchGroupResultSet('key')->toArray(),
            'locale' => $this->tableLocale->findLocales()->setFetchGroupResultSet('languageCode', 'relatedTableId')->toArray()
        ];

        $this->cacheData[__FUNCTION__] = $result;

        return $result;
    }
}
