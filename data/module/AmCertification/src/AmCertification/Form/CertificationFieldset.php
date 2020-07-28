<?php

namespace AmCertification\Form;

use Administrator\Filter\MediaUri;
use Administrator\Form\AdministratorFieldset;
use AmCertification\Model\CertificationTable;


class CertificationFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = CertificationTable::class;

    public function addElements()
    {
        $logo = $this->get('logo');
        $class = $logo->getAttribute('class');
        $class .= ' browsefile';
        $logo->setAttribute('class', $class);

        $pdf = $this->get('fileUrl');
        $class = $pdf->getAttribute('class');
        $class .= ' browsefile';
        $pdf->setAttribute('class', $class);
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilter['logo']['filters'][] = [
            'name' => MediaUri::class
        ];

        $inputFilter['fileUrl']['filters'][] = [
            'name' => MediaUri::class
        ];

        return $inputFilter;
    }
}
