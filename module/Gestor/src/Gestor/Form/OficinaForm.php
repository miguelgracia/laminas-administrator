<?php

namespace Gestor\Form;


use Zend\InputFilter;
use Zend\Form\Form;
use Zend\Form\Element;

class OficinaForm extends Form
{


    public function setServiceLocator($sm)
    {
        $this->sm = $sm;
    }


    public function addFields()
    {
        // Para que me lo pintes bien
        $this->setAttribute('class', 'form-horizontal');

        // Oficina
        $provinciaTable = $this->sm->get('Gestor\Model\ProvinciaTable');
        $provincia = $provinciaTable->fetchAll();
        // Montamos el array
        $arrayProvincia = array();
        foreach ($provincia as $i => $entrada) :
            $arrayProvincia[$entrada->id] = $entrada->nombre;
        endforeach;
        // Ahora metemos el campo select a partir de ellas
        $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'id' => 'idprovincia',
                'name' => 'idprovincia',
                'options' => array(
                    'value_options' => $arrayProvincia,
                    'label' => 'Provincia',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label',
                    ),
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            )
        );

        // Nombre
        $this->add(array(
            'name' => 'nombre',
            'options' => array(
                'label' => 'Nombre para PDF',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type' => 'text',
            'attributes' => array(
                'id' => 'nombre',
                'class' => 'form-control',
            ),
        ));

        // Dirección
        $this->add(array(
            'name' => 'direccion',
            'options' => array(
                'label' => 'Dirección',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type' => 'text',
            'attributes' => array(
                'id' => 'direccion',
                'class' => 'form-control',
            ),
        ));

        // Localidad
        $this->add(array(
            'name' => 'localidad',
            'options' => array(
                'label' => 'Localidad',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type' => 'text',
            'attributes' => array(
                'id' => 'localidad',
                'class' => 'form-control',
            ),
        ));

        // Codigo postal
        $this->add(array(
            'name' => 'cp',
            'options' => array(
                'label' => 'Código postal',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type' => 'text',
            'attributes' => array(
                'id' => 'cp',
                'class' => 'form-control',
            ),
        ));

        // Número de oficina
        $this->add(array(
            'name' => 'numerooficina',
            'options' => array(
                'label' => 'Número de oficina',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type' => 'text',
            'attributes' => array(
                'id' => 'numerooficina',
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

        $this->add(array(
            'name' => 'fecha_creacion',
            'type'  => 'hidden',
            'attributes' => array(
                'id' =>'fecha_creacion',
                'value' => 'fecha_creacion',
                'class' => 'form-control',
            ),
        ));





    }
}