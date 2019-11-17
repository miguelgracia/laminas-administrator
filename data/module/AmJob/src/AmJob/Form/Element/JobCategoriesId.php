<?php


namespace AmJob\Form\Element;

use AmJobCategory\Model\JobCategoryTable;
use Zend\Form\Element\Select;

class JobCategoriesId extends Select
{
    public function __construct(JobCategoryTable $model, $name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setValueOptions(
            $model->all()->toKeyValueArray('id', 'key')
        );
    }
}