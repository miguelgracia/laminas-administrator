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

        $result = array(
            'rows' => $this->table->all(array(
                'active' => '1',
                'deleted_at' => null
            ))->setFetchGroupResultSet('id')->toArray(),
            'locale' => $this->tableLocale->findLocales()->setFetchGroupResultSet('languageCode','urlKey')->toArray()
        );

        $this->cacheData[__FUNCTION__] = $result;

        return $result;
    }
}