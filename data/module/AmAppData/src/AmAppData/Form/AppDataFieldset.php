<?php

namespace AmAppData\Form;

use Administrator\Form\AdministratorFieldset;
use AmAppData\Model\AppDataTable;
use Zend\Validator\EmailAddress;

class AppDataFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = AppDataTable::class;

    public function addElements()
    {
        $key = $this->get('key');
        $key->setAttribute('readonly', 'readonly');
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $urlValidator = [
            'name' => 'Zend\Validator\Uri',
            'options' => [
                'allowRelative' => false
            ],
        ];

        $social = [
            'facebook',
            'twitter',
            'linkedin',
            'instagram'
        ];

        array_walk($social, function ($value) use (&$inputFilter,$urlValidator) {
            $inputFilter[$value]['validators'][] = $urlValidator;
        });

        $inputFilter['mailInbox']['validators'][] = [
            'name' => EmailAddress::class
        ];

        return  $inputFilter;
    }
}
