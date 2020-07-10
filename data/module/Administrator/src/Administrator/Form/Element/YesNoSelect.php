<?php

namespace Administrator\Form\Element;

use Laminas\Form\Element\Select;

class YesNoSelect extends Select
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setValueOptions([
            '0' => 'NO',
            '1' => 'SI'
        ]);
    }
}
