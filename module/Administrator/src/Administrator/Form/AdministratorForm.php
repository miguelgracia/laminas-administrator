<?php


namespace Administrator\Form;


use Zend\Form\Form;
use Zend\Form\FormInterface;

class AdministratorForm extends Form
{
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        $parent = parent::bind($object, $flags);

        return $parent;
    }
}