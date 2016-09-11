<?php

namespace Api\Service;

use Api\Model\JobLocaleTable;
use Api\Model\JobTable;
use Zend\ServiceManager\FactoryInterface;

class JobService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = JobTable::class;
    protected $tableLocale = JobLocaleTable::class;


    public function getData($lang, $page, $limit = 10)
    {

    }
}