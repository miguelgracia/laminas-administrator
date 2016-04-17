<?php

namespace Gestor\Form;


use Zend\InputFilter;
use Zend\Form\Form;
use Zend\Form\Element;

class GuiadeusoForm extends Form
{

    public function setServiceLocator($sm)
    {
        $this->sm = $sm;
    }


    public function addFields()
    {

        $this->setAttribute('class', 'form-horizontal');



        // La imagen...
        $this->add(array(
                'type' => 'file',
                'id' => 'pdf',
                'name' => 'pdf',
                'options' => array(
                    'label' => 'Guía de Uso',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label',
                    ),
                ),
                'value' => 'file',
            )
        );

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'post',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary',
            ),
            'options' => array(
                'label' => 'post',
            )
        ),array(
            'priority' => '-9999'
        ));


    }

}