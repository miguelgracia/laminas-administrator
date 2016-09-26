<?php

namespace AmAppData\Form;

use Administrator\Form\AdministratorFieldset;
use AmAppData\Model\AppDataLocaleTable;

class AppDataLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = AppDataLocaleTable::class;

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilter['email']['validators'][] = array(
            'name' => 'Zend\Validator\EmailAddress'
        );

        return  $inputFilter;
    }
}