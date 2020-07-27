<?php

namespace AmCertification\Form;

use Administrator\Filter\MediaUri;
use Administrator\Form\AdministratorFieldset;
use AmCertification\Model\CertificationTable;


class CertificationFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = CertificationTable::class;

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilter['imageUrl']['filters'][] = [
            'name' => MediaUri::class
        ];

        return $inputFilter;
    }
}
