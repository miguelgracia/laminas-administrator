<?php

namespace Api\Service;

use Api\Model\BlogCategoryLocaleTable;
use Api\Model\BlogCategoryTable;
use Zend\ServiceManager\FactoryInterface;

class BlogCategoryService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = BlogCategoryTable::class;
    protected $tableLocale = BlogCategoryLocaleTable::class;

    public function getData()
    {
        return array(
            'rows' => $this->table->all(array('deleted_at' => null))->setFetchGroupResultSet('id')->toArray(),
            'locale' => $this->tableLocale->findLocales()->setFetchGroupResultSet('languageCode','relatedTableId')->toArray()
        );
    }
}