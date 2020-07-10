<?php

namespace AmAppData\Form;

use Administrator\Form\AdministratorFieldset;
use AmAppData\Model\AppDataTable;
use Laminas\Validator\EmailAddress;

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
            'name' => 'Laminas\Validator\Uri',
            'options' => [
                'allowRelative' => false
            ],
        ];

        $social = [
            'facebook',
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
