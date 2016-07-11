<?php

namespace AmStaticPage\Form;

use Administrator\Form\AdministratorFieldset;
use AmStaticPage\Model\StaticPageTable;

class StaticPageFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = StaticPageTable::class;

    public function initializers()
    {
        return array(
            'fieldValueOptions' => array(
                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
            )
        );
    }

    public function addFields()
    {
        if ($this->formActionType == 'edit') {
            $this->get('key')->setAttribute('readonly','readonly');
        }
    }
}

