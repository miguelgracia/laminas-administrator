<?php

namespace Api\Service;

use Api\Model\PartnerTable;
use Laminas\Stdlib\ArrayObject;

class PartnerService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = PartnerTable::class;

    public function getData($lang)
    {
        return new ArrayObject(
            $this->table->all([
                'active' => '1',
                'deleted_at' => null
            ], ['order' => 'ASC'])->setFetchGroupResultSet('id')->toObjectArray(),
            ArrayObject::ARRAY_AS_PROPS
        );
    }
}
