<?php

namespace AmStaticPage\Form;

use Administrator\Form\AdministratorFieldset;
use AmStaticPage\Model\StaticPageTable;

class StaticPageFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = StaticPageTable::class;

    public function addElements()
    {
        if ($this->formActionType == 'edit') {
            $this->get('key')->setAttribute('readonly','readonly');
        }
    }
}

