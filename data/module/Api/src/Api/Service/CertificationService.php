<?php

namespace Api\Service;

use Api\Model\CertificationTable;
use Laminas\Stdlib\ArrayObject;

class CertificationService implements AllowDatabaseAccessInterface
{
    use AllowDatabaseAccessTrait;

    protected $tableName = CertificationTable::class;

    public function getData()
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
