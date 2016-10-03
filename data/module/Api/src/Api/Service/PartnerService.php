<?php

namespace Api\Service;

use Api\Model\PartnerLocaleTable;
use Api\Model\PartnerTable;
use Zend\ServiceManager\FactoryInterface;
use Zend\Stdlib\ArrayObject;

class PartnerService implements FactoryInterface
{
    use ApiServiceTrait;

    protected $table = PartnerTable::class;

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