<?php

namespace Api\Service;

use Api\Model\PartnerTable;
use Zend\Stdlib\ArrayObject;

class PartnerService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = PartnerTable::class;

    public function getData($lang)
    {
        $rows = new ArrayObject(

            $this->table->all(array(
                'active' => '1',
                'deleted_at' => null
            ))->setFetchGroupResultSet('id')->toObjectArray(),

            ArrayObject::ARRAY_AS_PROPS
        );

        return $rows;
    }
}