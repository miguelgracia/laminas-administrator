<?php

namespace AmJob\Form;

use Administrator\Filter\MediaUri;
use Administrator\Form\AdministratorFieldset;
use AmJob\Model\JobTable;

class JobFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = JobTable::class;

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilter['imageUrl']['filters'][] = array(
            'name' => MediaUri::class
        );

        return $inputFilter;
    }
}