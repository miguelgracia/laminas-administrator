<?php

namespace AmUser\Form;

use Administrator\Form\AdministratorFieldset;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserFieldset extends AdministratorFieldset
{
    public function initializers(ServiceLocatorInterface $serviceLocator)
    {
        return array(

            'fieldModifiers' => array(
                'gestorPerfilId' => 'Select',
                'password' => 'password'
            ),
            'fieldValueOptions' => array(
                /**
                 * El campo idPerfil se va a reflejar como un select de perfiles. Debemos indicar los valores
                 * de dicho select. La función setIdPerfil guarda, para el campo idPerfil, los valores que va
                 * a tener el <select> de idPerfil
                 */

                'gestorPerfilId' => function () use($serviceLocator) {

                    $gestorPerfilTable = $serviceLocator->get('AmProfile\Model\ProfileTable');

                    $gestorPerfil = $gestorPerfilTable->fetchAll();

                    return $gestorPerfil->toKeyValueArray('id','nombre');
                }
            )
        );
    }

    public function addFields()
    {
        //fechaAlta y ultimoLogin nunca deben ser editables. Se marcan como Readonly
        $this->get('createdAt')->setAttribute('readonly',true);
        $this->get('lastLogin')->setAttribute('readonly',true);

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
                'value' => '0',
            )
        ));

        return $this;
    }

    public function getInputFilterSpecification()
    {
        $filter = parent::getInputFilterSpecification();

        $filter['checkPassword'] = array(
            'name' => 'checkPassword',
            'required' => false
        );

        $checkPassword = $this->get('checkPassword')->getValue();

        if (is_null($checkPassword) or $checkPassword == '1') {

            $filter['password2'] = array(
                'name' => 'password2',
                'validators' => array(
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'password'
                        ),
                    ),
                ),
                'required' => true
            );

        } else {
            $filter['password']['required']  = false;
        }

        return $filter;
    }
}