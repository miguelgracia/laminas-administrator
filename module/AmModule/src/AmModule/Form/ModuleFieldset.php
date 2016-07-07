<?php

namespace AmModule\Form;


use Administrator\Form\AdministratorFieldset;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleFieldset extends AdministratorFieldset
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldModifiers' => array(

            ),
            'fieldValueOptions' => array(

            )
        );
    }

    public function addFields()
    {
        $this->get('zendName')->setAttribute('readonly','readonly');
    }
}