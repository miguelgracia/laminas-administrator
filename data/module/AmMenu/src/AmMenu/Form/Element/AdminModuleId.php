<?php

namespace AmMenu\Form\Element;

use AmModule\Model\ModuleTable;
use Zend\Form\Element\Select;

class AdminModuleId extends Select
{
    public function __construct(ModuleTable $moduleTable, $name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setValueOptions(
            ['' => '[Ninguno]'] + $moduleTable->all()->toKeyValueArray('id','zendName')
        );
    }

}