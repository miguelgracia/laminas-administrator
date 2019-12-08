<?php

namespace AmPartner\Form;

use Administrator\Form\AdministratorFieldset;
use AmPartner\Model\PartnerTable;

class PartnerFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = PartnerTable::class;

    public function addElements()
    {
        $logo = $this->get('logo');
        $class = $logo->getAttribute('class');
        $class .= ' browsefile';
        $logo->setAttribute('class', $class);
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilter['website']['validators'][] = [
            'name' => 'Zend\Validator\Uri',
            'options' => [
                'allowRelative' => false
            ],
        ];

        return  $inputFilter;
    }
}
