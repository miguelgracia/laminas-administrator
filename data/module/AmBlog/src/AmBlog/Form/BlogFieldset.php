<?php

namespace AmBlog\Form;

use Administrator\Filter\MediaUri;
use Administrator\Form\AdministratorFieldset;
use AmBlog\Model\BlogModel;
use AmBlog\Model\BlogTable;

class BlogFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = BlogTable::class;

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilter['imageUrl']['filters'][] = array(
            'name' => MediaUri::class
        );

        return $inputFilter;
    }
}