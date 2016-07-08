<?php

namespace AmSection\Form;

use Administrator\Form\AdministratorFieldset;
use Zend\ServiceManager\ServiceLocatorInterface;

class SectionFieldset extends AdministratorFieldset
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(
            'fieldValueOptions' => array(
                'position' => array(
                    'header' => 'cabecera',
                    'footer' => 'Pie de página'
                ),
            )
        );
    }

    public function addFields()
    {

    }
}

