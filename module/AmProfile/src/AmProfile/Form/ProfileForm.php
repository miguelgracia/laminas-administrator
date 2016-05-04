<?php

namespace AmProfile\Form;

use Zend\Form\Form;

class ProfileForm extends Form {

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'descripcion'   => 'textarea'
            ),
        );
    }
}