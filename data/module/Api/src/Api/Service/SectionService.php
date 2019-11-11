<?php

namespace Api\Service;

use Api\Model\SectionLocaleTable;
use Api\Model\SectionTable;
use Zend\Stdlib\ArrayObject;

class SectionService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = SectionTable::class;
    protected $tableLocaleName = SectionLocaleTable::class;

    public function getMenu()
    {
        $rows = new ArrayObject (
            $this->table->all(array('deleted_at' => null))->setFetchGroupResultSet('key')->toObjectArray(),
            ArrayObject::ARRAY_AS_PROPS
        );

        $locales = new ArrayObject (
            $this->tableLocale->findLocales()->setFetchGroupResultSet('languageCode','relatedTableId')->toObjectArray(),
            ArrayObject::ARRAY_AS_PROPS
        );

        return new ArrayObject(array(
            'rows' => $rows,
            'locale' => $locales
        ),ArrayObject::ARRAY_AS_PROPS);
    }


}