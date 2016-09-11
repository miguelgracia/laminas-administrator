<?php

namespace Api\Service;


use Api\Model\BlogLocaleTable;
use Api\Model\BlogTable;
use Zend\ServiceManager\FactoryInterface;

class BlogService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = BlogTable::class;
    protected $tableLocale = BlogLocaleTable::class;
}