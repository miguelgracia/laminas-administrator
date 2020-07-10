<?php

namespace AmAccessory\Form;

use Administrator\Filter\MediaUri;
use Administrator\Form\AdministratorFieldset;
use AmAccessory\Model\AccessoryTable;


class AccessoryFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = AccessoryTable::class;

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilter['imageUrl']['filters'][] = [
            'name' => MediaUri::class
        ];

        return $inputFilter;
    }
}
