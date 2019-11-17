<?php

namespace AmBlog\Form;

use Administrator\Form\AdministratorFieldset;
use AmBlog\Model\BlogLocaleTable;
use Zend\Form\Element\Textarea;

class BlogLocaleFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = BlogLocaleTable::class;

    public function addFields()
    {
        $metaDescription = $this->get('metaDescription');
        $classes = $metaDescription->getAttribute('class');
        $metaDescription->setAttribute('class', $classes . ' no-editor');
    }
}