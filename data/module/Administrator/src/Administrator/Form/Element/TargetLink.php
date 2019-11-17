<?php

namespace Administrator\Form\Element;

use Zend\Form\Element\Select;

class TargetLink extends Select
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setValueOptions([
            '_self' => '_self',
            '_blank' => '_blank'
        ]);
    }
}