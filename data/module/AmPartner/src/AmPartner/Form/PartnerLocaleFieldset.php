<?php

namespace AmPartner\Form;

use Administrator\Form\AdministratorFieldset;
use AmPartner\Model\PartnerLocaleTable;

class PartnerLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = PartnerLocaleTable::class;

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'content' => 'Textarea'
            )
        );
    }
}