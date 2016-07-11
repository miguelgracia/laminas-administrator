<?php

namespace AmBlogCategory\Form;

use Administrator\Form\AdministratorFieldset;
use AmBlogCategory\Model\BlogCategoryTable;

class BlogCategoryFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = BlogCategoryTable::class;

    public function initializers()
    {
        return array(
            'fieldValueOptions' => array(
                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
            )
        );
    }
}

