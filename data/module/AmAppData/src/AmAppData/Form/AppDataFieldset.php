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
        $key->setAttribute('readonly','readonly');
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $urlValidator = array(
            'name' => 'Zend\Validator\Uri',
            'options' => array(
                'allowRelative' => false
            ),
        );

        $social = array(
            'facebook',
            'twitter',
            'googlePlus',
            'instagram'
        );

        array_walk($social, function ($value) use(&$inputFilter,$urlValidator) {
            $inputFilter[$value]['validators'][] = $urlValidator;
        });

        $inputFilter['mailInbox']['validators'][] = array(
            'name' => EmailAddress::class
        );

        return  $inputFilter;
    }
}