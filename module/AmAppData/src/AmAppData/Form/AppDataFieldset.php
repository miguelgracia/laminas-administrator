<?php

namespace AmAppData\Form;

use Administrator\Form\AdministratorFieldset;
use AmAppData\Model\AppDataTable;

class AppDataFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = AppDataTable::class;

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'companyInfo' => 'Textarea'
            ),
        );
    }

    public function addFields()
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
            'google_plus',
            'instagram'
        );

        array_walk($social, function ($value) use(&$inputFilter,$urlValidator) {
            $inputFilter[$value]['validators'][] = $urlValidator;
        });
        
        return  $inputFilter;
    }
}