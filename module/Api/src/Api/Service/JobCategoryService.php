<?php

namespace Api\Service;

use Api\Model\JobCategoryLocaleTable;
use Api\Model\JobCategoryTable;
use Zend\ServiceManager\FactoryInterface;

class JobCategoryService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = JobCategoryTable::class;
    protected $tableLocale = JobCategoryLocaleTable::class;
}