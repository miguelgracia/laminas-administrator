<?php

namespace Api\Service;

use Api\Model\MegabannerLocaleTable;
use Api\Model\MegabannerTable;
use Zend\Stdlib\ArrayObject;

class MegabannerService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = MegabannerTable::class;

    public function getData($lang)
    {
        $rows = new ArrayObject(

            $this->table->all(array(
                'active' => '1',
                'deleted_at' => null
            ), array('order ASC'))->setFetchGroupResultSet('id')->toObjectArray(),

            ArrayObject::ARRAY_AS_PROPS
        );

        return $rows;
    }
}