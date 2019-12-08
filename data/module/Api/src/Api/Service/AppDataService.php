<?php

namespace Api\Service;

use Api\Model\AppDataLocaleTable;
use Api\Model\AppDataTable;
use Zend\Stdlib\ArrayObject;

class AppDataService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = AppDataTable::class;
    protected $tableLocaleName = AppDataLocaleTable::class;

    public function getData()
    {
        $row = $this->table->find('app-data', 'key');

        $locales = $this->tableLocale->findLocales($row->id)->setFetchGroupResultSet('languageCode');
        $localeObj = new \stdClass();
        $locales->toObjectArray($localeObj);

        return new ArrayObject([
            'row' => $row->getObjectCopy(),
            'locale' => $localeObj
        ], ArrayObject::ARRAY_AS_PROPS);
    }
}
