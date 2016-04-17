<?php

namespace Gestor\Form;

use Zend\Form\Form;

class GestorUsuariosForm extends Form {

    public function initializers()
    {
        return array(

            'fieldModifiers' => array(
                'idPerfil' => 'Select',
                'password' => 'password'
            ),
            'fieldValueOptions' => array(
                /**
                 * El campo idPerfil se va a reflejar como un select de perfiles. Debemos indicar los valores
                 * de dicho select. La función setIdPerfil guarda, para el campo idPerfil, los valores que va
                 * a tener el <select> de idPerfil
                 */

                'idPerfil' => function ($serviceLocator) {

                    $gestorPerfilTable = $serviceLocator->get('Gestor\Model\PerfilTable');

                    $misPermisos = $serviceLocator->get('Gestor\Factory\PermisosCheckerFactory');

                    $gestorPerfil = $misPermisos->isAdmin()
                        ? $gestorPerfilTable->fetchAll()
                        : $gestorPerfilTable->fetchHijos($misPermisos->idPerfil);

                    return $gestorPerfil->toKeyValueArray('id','nombre');
                }
            )
        );
    }

    public function addFields()
    {
        //fechaAlta y ultimoLogin nunca deben ser editables. Se marcan como Readonly
        $this->get('fechaAlta')->setAttribute('readonly',true);
        $this->get('ultimoLogin')->setAttribute('readonly',true);

        $this->add(array(
            'name' => 'password2',
            'type'  => 'password',
            'label' => 'Repetir password',
            'options' => array(
                'label' => 'Repetir password',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label'
                ),
            ),
            'attributes' => array(
                'id' =>'password2',
                'value' => '',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'checkPassword',
            'type' => 'Zend\Form\Element\Checkbox',
            'label' => 'Change Password',
            'options' => array(
                'use_hidden_element' => true,
                'label' => 'Change Password',
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
            'attributes' => array(
                'id' => 'change_password',
                'value' => '0'
            )
        ));

        return $this;
    }
}