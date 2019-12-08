<?php

namespace AmUser\Form;

use Administrator\Form\AdministratorFieldset;
use AmUser\Model\UserTable;

class UserFieldset extends AdministratorFieldset
{
    protected $isPrimaryFieldset = true;

    protected $tableGatewayName = UserTable::class;

    public function getHiddenFields()
    {
        $hiddenFields = parent::getHiddenFields();

        $hiddenFields = array_merge($hiddenFields, [
            'lastLogin',
            'validado'
        ]);

        return $hiddenFields;
    }

    public function addElements()
    {
        $this->add([
            'name' => 'password2',
            'type' => 'password',
            'label' => 'Repetir password',
            'options' => [
                'label' => 'Repetir password',
                'label_attributes' => [
                    'class' => 'col-sm-2 control-label'
                ],
            ],
            'attributes' => [
                'id' => 'password2',
                'value' => '',
                'class' => 'form-control',
            ],
        ]);

        if ($this->get('id')->getValue() !== null) {
            $this->add([
                'name' => 'checkPassword',
                'type' => 'Zend\Form\Element\Checkbox',
                'label' => 'Change Password',
                'options' => [
                    'use_hidden_element' => true,
                    'label' => 'Change Password',
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                ],
                'attributes' => [
                    'id' => 'change_password',
                    'value' => '0',
                ]
            ], [
                'priority' => -300,
            ]);
        }

        return $this;
    }

    public function getInputFilterSpecification()
    {
        $filter = parent::getInputFilterSpecification();

        $filter['username']['validators'][] = [
            'name' => 'Zend\Validator\Db\NoRecordExists',
            'options' => [
                'table' => $this->tableGateway->getTable(),
                'field' => 'username',
                'adapter' => $this->tableGateway->getAdapter(),
                'exclude' => [
                    'field' => 'id',
                    'value' => $this->get('id')->getValue()
                ]
            ]
        ];

        $filter['password2'] = [
            'name' => 'password2',
            'validators' => [
                [
                    'name' => 'Identical',
                    'options' => [
                        'token' => 'password'
                    ],
                ],
            ],
            'required' => true
        ];

        if ($this->get('id')->getValue() !== null) {
            $filter['checkPassword'] = [
                'name' => 'checkPassword',
            ];

            $checkPassword = $this->get('checkPassword')->getValue();

            if ($checkPassword == '0') {
                $filter['password']['required'] = false;
                $filter['password2']['required'] = false;
            }
        }

        return $filter;
    }
}
