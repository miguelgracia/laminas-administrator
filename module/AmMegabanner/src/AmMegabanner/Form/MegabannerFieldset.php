<?php

namespace AmMegabanner\Form;

use Administrator\Form\AdministratorFieldset;

class MegabannerFieldset extends AdministratorFieldset
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
}