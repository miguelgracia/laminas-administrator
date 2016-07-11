<?php

namespace AmUser\Form;

use Administrator\Form\AdministratorFieldset;
use AmUser\Model\UserTable;

class UserFieldset extends AdministratorFieldset
{
    protected $tableGatewayName = UserTable::class;

    public function getHiddenFields()
    {
        $hiddenFields = parent::getHiddenFields();

        $hiddenFields = array_merge($hiddenFields, array(
            'lastLogin',
            'validado'
        ));

        return $hiddenFields;
    }

    public function initializers()
    {
        $serviceLocator = $this->serviceLocator->getServiceLocator();

        return array(

            'fieldModifiers' => array(
                'adminProfileId' => 'Select',
                'password' => 'password'
            ),
            'fieldValueOptions' => array(
                'active' => array(
                    '0' => 'NO',
                    '1' => 'SI'
                ),
                /**
                 * El campo idPerfil se va a reflejar como un select de perfiles. Debemos indicar los valores
                 * de dicho select. La funci�n setIdPerfil guarda, para el campo idPerfil, los valores que va
                 * a tener el <select> de idPerfil
                 */

                'adminProfileId' => function () use($serviceLocator) {

                    $gestorPerfilTable = $serviceLocator->get('AmProfile\Model\ProfileTable');

                    $gestorPerfil = $gestorPerfilTable->all();

                    return $gestorPerfil->toKeyValueArray('id','name');
                }
            )
        );
    }

    public function addFields()
    {
        //fechaAlta y ultimoLogin nunca deben ser editables. Se marcan como Readonly
        //$this->get('createdAt')->setAttribute('readonly',true);
        //$this->get('lastLogin')->setAttribute('readonly',true);

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
        ),array(
            'priority' => -400,
        ));

        if ($this->formActionType == 'edit') {

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
            ),array(
                'priority' => -300,
            ));
        }


        return $this;
    }

    public function getInputFilterSpecification()
    {
        $filter = parent::getInputFilterSpecification();

        $filter['username']['validators'][] = array(
            'name' => 'Zend\Validator\Db\NoRecordExists',
            'options' => array(
                'table' => $this->tableGateway->getTable(),
                'field' => 'username',
                'adapter' => $this->tableGateway->getAdapter(),
                'exclude' => array(
                    'field' => 'id',
                    'value' => $this->get('id')->getValue()
                )
            )
        );

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

        if ($this->formActionType == 'edit') {

            $filter['checkPassword'] = array(
                'name' => 'checkPassword',
            );

            $checkPassword = $this->get('checkPassword')->getValue();

            if ($checkPassword == '0') {
                $filter['password']['required']  = false;
                $filter['password2']['required']  = false;
            }
        }

        return $filter;
    }
}