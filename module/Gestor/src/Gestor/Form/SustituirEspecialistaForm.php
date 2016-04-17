<?php

namespace Gestor\Form;


use Zend\InputFilter;
use Zend\Form\Form;
use Zend\Form\Element;

class SustituirEspecialistaForm extends Form {

    public function setServiceLocator ($sm)
    {
        $this->sm = $sm;
    }

    public function addFields($idEspecialista,$especialistaEditar, $equiposEspecialista)
    {
        $this->setAttribute('class', 'form-horizontal');

        $especialistaTable = $this->sm->get('Gestor\Model\EspecialistaTable');

        //Recogemos los especialistas que pertenecen a la misma especialidad del especialista a sustituir
        $especialistas = $especialistaTable->fetchFromEspecialidad($especialistaEditar->idespecialidad);

        $value_options = $especialistas->toKeyValueArray('id',array('nombre','apellido1','apellido2'));

        foreach ($value_options as $key => &$option) {
            $option = array('label' => $option, 'value' => $key, 'selected' => ((int) $idEspecialista === (int) $key));
        }

        foreach ($equiposEspecialista as $key => $equipoRelacion) {

            // Ahora metemos el campo select a partir de ellas
            $select = new Element\Select('idespecialista_'.$equipoRelacion['id'], array(
                'label' => 'Equipo ' . $equipoRelacion['idEquipo'] . " - " . $equipoRelacion["director"],
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label',
                ),
            ));
            $select->setAttributes(array(
                'class' => 'form-control col-sm-12 js-select-especialistas',
                'id' => 'idespecialista_'.$equipoRelacion['id']
            ));

            $select->setValueOptions($value_options);

            $this->add($select);
        }


        $this->add(array(
            'name' => 'id',
            'type'  => 'hidden',
            'attributes' => array(
                'id' =>'id',
                'value' => $idEspecialista,
                'class' => 'form-control',
            ),
        ));
    }
}