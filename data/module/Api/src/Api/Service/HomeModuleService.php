<?php

namespace Api\Service;

use Api\Model\HomeModuleLocaleTable;
use Api\Model\HomeModuleTable;
use Zend\Stdlib\ArrayObject;

class HomeModuleService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = HomeModuleTable::class;
    protected $tableLocaleName = HomeModuleLocaleTable::class;

    public function getData($lang)
    {
        $rows = new ArrayObject(
            $this->table->all([
                'active' => '1',
                'deleted_at' => null
            ])->setFetchGroupResultSet('id')->toObjectArray(),
            ArrayObject::ARRAY_AS_PROPS
        );

        $locales = $this->tableLocale->findByLangCode($lang)->setFetchGroupResultSet('relatedTableId')->toObjectArray();

        foreach ($rows as &$row) {
            $row->locale = $locales[$row->id];
        }

        return $rows;
    }
}
