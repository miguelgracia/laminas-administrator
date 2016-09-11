<?php

namespace Api\Service;

use Api\Model\JobCategoryLocaleTable;
use Api\Model\JobCategoryTable;
use Zend\ServiceManager\FactoryInterface;
use Zend\Stdlib\ArrayObject;

class JobCategoryService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = JobCategoryTable::class;
    protected $tableLocale = JobCategoryLocaleTable::class;

    public function getData()
    {
        return array(
            'rows' => $this->table->all(array('deleted_at' => null))->setFetchGroupResultSet('id')->toArray(),
            'locale' => $this->tableLocale->findLocales()->setFetchGroupResultSet('languageCode','relatedTableId')->toArray()
        );
    }
}