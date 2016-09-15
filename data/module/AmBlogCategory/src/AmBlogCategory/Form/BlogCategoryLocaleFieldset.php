<?php

namespace AmBlogCategory\Form;

use Administrator\Form\AdministratorFieldset;
use AmBlogCategory\Model\BlogCategoryLocaleTable;

class BlogCategoryLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = BlogCategoryLocaleTable::class;

    public function initializers()
    {
        return array(
            'fieldModifiers' => array(
                'blogCategoriesId' => 'Hidden',
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
            'languageId'
        );
    }
}

