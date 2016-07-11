<?php

namespace AmStaticPage\Form;

use Administrator\Form\AdministratorFieldset;

class StaticPageFieldset extends AdministratorFieldset
{
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

