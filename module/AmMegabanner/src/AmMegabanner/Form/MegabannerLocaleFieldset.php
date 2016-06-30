<?php

namespace AmMegabanner\Form;

use Administrator\Form\AdministratorFieldset;

class MegabannerLocaleFieldset extends AdministratorFieldset
{
    public function addFields()
    {
        $imageUrl = $this->get('imageUrl');
        $class = $imageUrl->getAttribute('class');
        $class .= ' browsefile';
        $imageUrl->setAttribute('class',$class);
    }

    public function getHiddenFields()
    {
        return array(
            'locale',
            'languageId'
        );
    }
}