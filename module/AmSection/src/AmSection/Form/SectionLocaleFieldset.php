<?php

namespace AmSection\Form;

use Administrator\Form\AdministratorFieldset;

class SectionLocaleFieldset extends AdministratorFieldset
{
    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'metaDescription'   => 'Textarea'
            )
        );
    }

    public function addFields()
    {
        $metaDescription = $this->get('metaDescription');
        $classes = $metaDescription->getAttribute('class');
        $metaDescription->setAttribute('class', $classes . ' no-editor');
    }

    public function getHiddenFields()
    {
        return array(
            'locale',
            'languageId'
        );
    }
}