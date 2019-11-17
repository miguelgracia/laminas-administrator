<?php


namespace AmBlog\Form\Element;

use Api\Model\BlogCategoryTable;
use Zend\Form\Element\Select;

class BlogCategoriesId extends Select
{
    public function __construct(BlogCategoryTable $model, $name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setValueOptions(
            $model->all()->toKeyValueArray('id', 'key')
        );
    }
}