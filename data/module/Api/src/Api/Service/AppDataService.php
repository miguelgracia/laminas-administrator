<?php

namespace Api\Service;

use Api\Model\AppDataLocaleTable;
use Api\Model\AppDataTable;
use Zend\ServiceManager\FactoryInterface;
use Zend\Stdlib\ArrayObject;

class AppDataService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table       = AppDataTable::class;
    protected $tableLocale = AppDataLocaleTable::class;

    public function getData ()
    {
        $row = $this->table->find('app-data','key');

        $locales = $this->tableLocale->findLocales($row->id)->setFetchGroupResultSet('languageCode');
        $localeObj = new \stdClass();
        $locales->toObjectArray($localeObj);

        return new ArrayObject(array(
            'row' => $row->getObjectCopy(),
            'locale' => $localeObj
        ),ArrayObject::ARRAY_AS_PROPS);

    }
}