<?php

namespace Gestor\Form;


use Zend\InputFilter;
use Zend\Form\Form;
use Zend\Form\Element;

class DirectorRelacionForm extends Form
{

    public function setServiceLocator($sm)
    {
        $this->sm = $sm;
    }


    public function addFields($addSelect = 0)
    {
        // Para que me lo pintes bien
        $this->setAttribute('class', 'form-horizontal');


        // Direccion Regional, sacamos todas
        $direccionRegionalTable = $this->sm->get('Gestor\Model\DireccionRegionalTable');
        $direccionRegional = $direccionRegionalTable->fetchAll();
        // Montamos el array
        $arrayDR = array();
        if ($addSelect == 1) {
            $arrayDR[0] = 'Selecciona una opción';
        }
        foreach ($direccionRegional as $i => $entrada) :
            $arrayDR[$entrada->id] = $entrada->nombre;
        endforeach;
        // Ahora metemos el campo select a partir de ellas
        $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'id' => 'iddireccionregional',
                'name' => 'iddireccionregional',
                'options' => array(
                    'value_options' => $arrayDR,
                    'label' => 'Dirección Regional (DR)',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label',
                    ),
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            )
        );


        // Cargo, sacamos todos
        $cargoTable = $this->sm->get('Gestor\Model\CargoTable');
        $cargo = $cargoTable->fetchAll();
        // Montamos el array
        $arrayCargo = array();
        if ($addSelect == 1) {
            $arrayCargo[0] = 'Selecciona una opción';
        }
        foreach ($cargo as $i => $entrada) :
            $arrayCargo[$entrada->id] = $entrada->nombreInterno;
        endforeach;
        // Ahora metemos el campo select a partir de ellas
        $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'id' => 'idcargo',
                'name' => 'idcargo',
                'options' => array(
                    'value_options' => $arrayCargo,
                    'label' => 'Cargo',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label',
                    ),
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            )
        );


        // Oficina
        $oficinaTable = $this->sm->get('Gestor\Model\OficinaTable');
        $oficina = $oficinaTable->fetchAll();
        // Montamos el array
        $arrayOficina = array();
        if ($addSelect == 1) {
            $arrayOficina[0] = 'Selecciona una opción';
        }
        foreach ($oficina as $i => $entrada) :
            $arrayOficina[$entrada->id] = $entrada->numerooficina." (".$entrada->direccion.", ".$entrada->cp.", ".$entrada->localidad.", ".$entrada->nombreprovincia.")";
        endforeach;
        // Ahora metemos el campo select a partir de ellas
        $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'id' => 'idoficina',
                'name' => 'idoficina',
                'options' => array(
                    'value_options' => $arrayOficina,
                    'label' => 'Oficina',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label',
                    ),
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            )
        );



        // Usuario
        $this->add(array(
            'name' => 'login',
            'options' => array (
                'label' => 'Usuario (login)',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'login',
                'class' => 'form-control',
            ),
        ));



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
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'movil',
            'options' => array (
                'label' => 'Teléfono móvil',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'movil',
                'class' => 'form-control',
            ),
        ));



                $this->add(array(
                    'name' => 'email',
                    'options' => array (
                        'label' => 'Email',
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


        $this->add(array(
            'name' => 'idcoordinador',
            'type'  => 'hidden',
            'attributes' => array(
                'id' =>'idcoordinador',
                'value' => 'idcoordinador',
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