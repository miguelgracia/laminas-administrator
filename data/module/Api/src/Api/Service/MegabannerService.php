<?php

namespace Api\Service;

use Api\Model\MegabannerLocaleTable;
use Api\Model\MegabannerTable;
use Zend\ServiceManager\FactoryInterface;
use Zend\Stdlib\ArrayObject;

class MegabannerService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = MegabannerTable::class;
    protected $tableLocale = MegabannerLocaleTable::class;

    public function getData($lang)
    {
        $rows = new ArrayObject(

            $this->table->all(array(
                'active' => '1',
                'deleted_at' => null
            ), array('order ASC'))->setFetchGroupResultSet('id')->toObjectArray(),

            ArrayObject::ARRAY_AS_PROPS
        );

        $locales = $this->tableLocale->findByLangCode($lang)->setFetchGroupResultSet('relatedTableId')->toObjectArray();

        foreach ($rows as &$row) {
            $row->locale = $locales[$row->id];
        }

        return $rows;
    }
}