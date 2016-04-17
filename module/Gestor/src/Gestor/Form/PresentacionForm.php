<?php

namespace Gestor\Form;


use Zend\InputFilter;
use Zend\Form\Form;
use Zend\Form\Element;

class PresentacionForm extends Form
{

    public function setServiceLocator($sm)
    {
        $this->sm = $sm;
    }




    public function addFields()
    {

        $this->setAttribute('class', 'form-horizontal');

        // Fase 1 : Nombre de empresa

        $this->add(array(
            'name' => 'nombre_empresa',
            'options' => array (
                'label' => 'Nombre de la empresa',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'nombre_empresa',
                'class' => 'form-control',
            ),
        ));


        // Bloque de director

        $this->add(array(
            'name' => 'do_nombre',
            'options' => array (
                'label' => 'Nombre',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'do_nombre',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'do_email',
            'options' => array (
                'label' => 'Correo electrónico',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'do_email',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'do_telefono',
            'options' => array (
                'label' => 'Teléfono',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'do_telefono',
                'class' => 'form-control',
            ),
        ));

        // Bloque de gestion

        $this->add(array(
            'name' => 'ge_nombre',
            'options' => array (
                'label' => 'Nombre',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'ge_nombre',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'ge_email',
            'options' => array (
                'label' => 'Correo electrónico',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'ge_email',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'ge_telefono',
            'options' => array (
                'label' => 'Teléfono',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'text',
            'attributes' => array(
                'id' =>'ge_telefono',
                'class' => 'form-control',
            ),
        ));





/*
        $this->add(array(
            'name' => 'telefonos',
            'options' => array (
                'label' => 'Listado de teléfonos',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ),
            'type'  => 'textarea',
            'attributes' => array(
                'id' =>'telefonos',
                'class' => 'form-control',
                'rows' => 5,
            ),
        ));
*/



        // Fase 4 : Lenguajes
/*
        // Sacamos los lenguajes
        $lenguajeTable = $this->sm->get('Gestor\Model\LenguajeTable');
        $lenguaje = $lenguajeTable->fetchAll();
        // Montamos el array
        $arrayLenguajes = array();
        $arrayLenguajes[0] = "Todos";
        foreach ($lenguaje as  $i => $entrada) :
            $arrayLenguajes[$entrada->id] = $entrada->nombreInterno;
        endforeach;
        // Ahora metemos el campo select a partir de ellas
        $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'id' => 'idlenguaje',
                'name' => 'idlenguaje',
                'options' => array(
                    'value_options' => $arrayLenguajes,
                    'label' => 'Lenguaje',
                    'label_attributes' => array(
                        'class' => 'col-sm-2 control-label',
                    ),
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            )
        );
*/

    }

}


