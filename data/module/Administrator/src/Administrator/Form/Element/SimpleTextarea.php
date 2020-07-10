<?php

namespace Administrator\Form\Element;

use Laminas\Form\Element\Textarea;

class SimpleTextarea extends Textarea
{
    public function init()
    {
        $this->setAttribute('class', 'no-editor');
    }
}
