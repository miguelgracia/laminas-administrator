<?php

namespace AmModule\Form;

use Administrator\Form\AdministratorFieldset;

class ModuleFieldset extends AdministratorFieldset
{
    public function initializers()
    {
        return array(
            'fieldModifiers' => array(

            ),
            'fieldValueOptions' => array(

            )
        );
    }

    public function addFields()
    {
        $this->get('zendName')->setAttribute('readonly','readonly');
    }
}