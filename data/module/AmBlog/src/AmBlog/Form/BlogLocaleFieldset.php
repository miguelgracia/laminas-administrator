<?php

namespace AmBlog\Form;

use Administrator\Form\AdministratorFieldset;
use AmBlog\Model\BlogLocaleTable;

class BlogLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = BlogLocaleTable::class;

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
}