<?php

namespace AmModule\Form;

use Zend\Form\Form;

class ModuleForm extends Form {

    public function addFields()
    {
         $this->get('nombreZend')->setAttribute('readonly','readonly');
    }
}