<?php

namespace Api\Service;

use Api\Model\StaticPageLocaleTable;
use Api\Model\StaticPageTable;
use Zend\ServiceManager\FactoryInterface;
use Zend\Stdlib\ArrayObject;

class StaticPageService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = StaticPageTable::class;
    protected $tableLocale = StaticPageLocaleTable::class;

    protected $cacheData = array();

    public function getData()
    {
        if (array_key_exists(__FUNCTION__, $this->cacheData)) {
            return $this->cacheData[__FUNCTION__];
        }

        $rows = new ArrayObject(
            $this->table->all(array(
                'active' => '1',
                'deleted_at' => null
            ))->setFetchGroupResultSet('id')->toObjectArray(),
            ArrayObject::ARRAY_AS_PROPS
        );

        $locales = new ArrayObject(
            $this->tableLocale->findLocales()->setFetchGroupResultSet('languageCode','urlKey')->toObjectArray(),
            ArrayObject::ARRAY_AS_PROPS
        );

        $result = new ArrayObject(array(
            'rows' => $rows,
            'locale' => $locales
        ),ArrayObject::ARRAY_AS_PROPS);

        $this->cacheData[__FUNCTION__] = $result;

        return $result;
    }
}