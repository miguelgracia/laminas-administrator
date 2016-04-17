<?php

namespace Gestor\Form;

use Zend\Form\Form;

class DatosAccesoForm extends Form {

    function __construct($name, $options = array())
    {
        $this->setAttribute('class','form-horizontal');

        parent::__construct($name, $options);
    }

    public function addFields($userData)
    {
        if ($userData->idPerfil != 1) {
            //El usuario es un coordinador, debe introducir un email
            $this->add(array(
                'name' => 'email',
                'type' => 'text',
                'label' => 'Correo electrónico',
                'options' => array(
                    'label' => 'Correo electrónico',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label'
                    ),
                ),
                'attributes' => array(
                    'id' =>'email',
                    'value' => '',
                    'class' => 'form-control',
                ),
            ));
        }

        if ($userData->idPerfil == 3) {
            //El usuario es un director de relación. Debe meter también el número de teléfono
            $this->add(array(
                'name' => 'telefono',
                'type' => 'text',
                'label' => 'Teléfono',
                'options' => array(
                    'label' => 'Teléfono',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label'
                    ),
                ),
                'attributes' => array(
                    'id' =>'telefono',
                    'value' => '',
                    'class' => 'form-control',
                ),
            ));
        }

        $this->add(array(
            'name' => 'password',
            'type'  => 'password',
            'label' => 'Contraseña',
            'options' => array(
                'label' => 'Contraseña',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label'
                ),
            ),
            'attributes' => array(
                'id' =>'password',
                'value' => '',
                'class' => 'form-control',
            ),
        ))
            ->add(array(
                'name' => 'password2',
                'type'  => 'password',
                'label' => 'Repita su contraseña',
                'options' => array(
                    'label' => 'Repita su contraseña',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label'
                    ),
                ),
                'attributes' => array(
                    'id' =>'password2',
                    'value' => '',
                    'class' => 'form-control',
                ),
            ))

            ->add(array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                    'value' => 'Guardar',
                    'id' => 'submitbutton',
                    'class' => 'btn btn-primary',
                ),
                'options' => array(
                    'label' => 'Editar',
                )
            ),array(
                'priority' => '-9999'
            ));
    }
}