<?php

namespace AmHomeModule\Form;

use Administrator\Form\AdministratorFieldset;
use AmHomeModule\Model\HomeModuleLocaleTable;

class HomeModuleLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = HomeModuleLocaleTable::class;

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'content'           => 'Textarea',
            ),
        );
    }

    public function addFields()
    {
        $imageUrl = $this->get('imageUrl');
        $class = $imageUrl->getAttribute('class');
        $class .= ' browsefile';
        $imageUrl->setAttribute('class',$class);
    }

    public function getHiddenFields()
    {
        return array(
            'languageId'
        );
    }

}

