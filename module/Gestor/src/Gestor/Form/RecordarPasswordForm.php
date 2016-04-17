<?php

namespace Gestor\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class RecordarPasswordForm extends Form {

    function __construct($name, $options = array())
    {
        $this->setAttribute('class','form-horizontal');

        parent::__construct($name, $options);

        $this->addFields();
        $this->setInputFilter($this->recordadPasswordFilter());
    }

    public function recordadPasswordFilter()
    {
        $filter = new InputFilter();
        $filter->add(array(
            'name' => 'login',
            'required' => true
        ))->add(array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                )
            )
        ));

        return $filter;
    }

    public function addFields()
    {
        $this->add(array(
            'name' => 'login',
            'type' => 'text',
            'label' => 'Usuario',
            'options' => array(
                'label' => 'Usuario',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label'
                ),
            ),
            'attributes' => array(
                'id' =>'login',
                'value' => '',
                'class' => 'form-control',
            ),
        ))->add(array(
            'name' => 'email',
            'type' => 'text',
            'label' => 'Email',
            'options' => array(
                'label' => 'Email',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label'
                ),
            ),
            'attributes' => array(
                'id' =>'email',
                'value' => '',
                'class' => 'form-control',
            ),
        ))->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Enviar',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary btn-block btn-flat',
            ),
            'options' => array(
                'label' => 'Editar',
            )
        ),array(
            'priority' => '-9999'
        ));;
    }
}