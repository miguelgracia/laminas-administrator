<?php

namespace AmYouTube\Form\Element;

use Laminas\Form\Element\Select;

class Visibility extends Select
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setValueOptions([
            'public' => 'Público',
            'private' => 'Privado',
            'unlisted' => 'Oculto'
        ]);
    }
}
