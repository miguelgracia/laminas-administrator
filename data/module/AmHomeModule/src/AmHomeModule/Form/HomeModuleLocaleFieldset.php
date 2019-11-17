<?php

namespace AmHomeModule\Form;

use Administrator\Filter\MediaUri;
use Administrator\Form\AdministratorFieldset;
use AmHomeModule\Model\HomeModuleLocaleTable;

class HomeModuleLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = HomeModuleLocaleTable::class;

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilter['imageUrl']['filters'][] = array(
            'name' => MediaUri::class
        );

        return $inputFilter;
    }
}