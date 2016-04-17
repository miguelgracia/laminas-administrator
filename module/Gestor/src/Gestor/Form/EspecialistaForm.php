<?php

namespace Gestor\Form;


use Zend\InputFilter;
use Zend\Form\Form;
use Zend\Form\Element;

class EspecialistaForm extends Form {

    public function setServiceLocator ($sm)
    {
        $this->sm = $sm;
    }




    public function addFields()
    {

        $this->setAttribute('class', 'form-horizontal');



        // La imagen...
        $this->add(array(
                'type' => 'file',
                'id' => 'foto',
                'name' => 'foto',
                'options' => array(
                    'label' => 'Imagen del especialista',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label',
                    ),
                ),
                'value' => 'file',
            )
        );




        // Especialidad, sacamos todas
        $especialidadTable = $this->sm->get('Gestor\Model\EspecialidadTable');
        $especialidad = $especialidadTable->fetchAll();
        // Montamos el array
        $arrayEspecialidades = array();
        foreach ($especialidad as  $i => $entrada) :
            $arrayEspecialidades[$entrada->id] = $entrada->nombreInterno;
        endforeach;
        // Ahora metemos el campo select a partir de ellas
        $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'id' => 'idespecialidad',
                'name' => 'idespecialidad',
                'options' => array(
                    'value_options' => $arrayEspecialidades,
                    'label' => 'Especialidad',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label',
                    ),
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            )
        );




        // Nombre y apellidos

        $this->add(array(
            'name' => 'nombre',
            'options' => array (
                    'label' => 'Nombre',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label',
                    ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'nombre',
                //'value' => 'nombre',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'apellido1',
            'options' => array (
                'label' => 'Primer apellido',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'apellido1',
                //'value' => 'apellido1',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'apellido2',
            'options' => array (
                'label' => 'Segundo apellido',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'apellido2',
                //'value' => 'apellido2',
                'class' => 'form-control',
            ),
        ));


        // Valores hidden (id, fecha_creacion)

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