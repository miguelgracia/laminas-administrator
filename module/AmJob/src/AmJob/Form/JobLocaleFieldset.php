<?php

namespace AmJob\Form;

use Administrator\Form\AdministratorFieldset;

class JobLocaleFieldset extends AdministratorFieldset
{
    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'content'           => 'Textarea',
                'metaDescription'   => 'Textarea'
            ),
            'fieldValueOptions' => array(

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

