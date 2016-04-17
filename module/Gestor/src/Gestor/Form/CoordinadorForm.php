<?php

namespace Gestor\Form;


use Zend\InputFilter;
use Zend\Form\Form;
use Zend\Form\Element;

class CoordinadorForm extends Form
{

    public function setServiceLocator($sm)
    {
        $this->sm = $sm;
    }


    public function addFields()
    {
        // Para que me lo pintes bien
        $this->setAttribute('class', 'form-horizontal');

        $this->add(array(
            'name' => 'email',
            'options' => array (
                'label' => 'Correo electrónico',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'email',
                'class' => 'form-control',
            ),
        ));

        // Los hidden de id y fecha de creacion
        $this->add(array(
            'name' => 'id',
            'type'  => 'hidden',
            'attributes' => array(
                'id' =>'id',
                'value' => 'id',
                'class' => 'form-control',
            ),
        ));


    }


}