<?php

namespace AmUser\Form\Element;

use AmProfile\Model\ProfileTable;
use Zend\Form\Element\Select;

class AdminProfileId extends Select
{
    public function __construct(ProfileTable $profileTable, $name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setValueOptions(
            $profileTable->all()->toKeyValueArray('id', 'name')
        );
    }
}
