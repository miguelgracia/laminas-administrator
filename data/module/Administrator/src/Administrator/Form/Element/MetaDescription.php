<?php

namespace Administrator\Form\Element;

use Zend\Form\Element\Textarea;

class MetaDescription extends Textarea
{
    public function init()
    {
        $this->setAttribute('class', 'no-editor');
    }
}
