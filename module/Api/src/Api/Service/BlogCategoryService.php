<?php

namespace Api\Service;

use Zend\ServiceManager\FactoryInterface;

class BlogCategoryService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = null;
    protected $tableLocale = null;
}