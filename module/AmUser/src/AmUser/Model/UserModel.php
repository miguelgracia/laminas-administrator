<?php

namespace AmUser\Model;

use Administrator\Model\AdministratorModel;

class UserModel extends AdministratorModel
{
    public function getInputFilter()
    {
        $inputFilter =  parent::getInputFilter();

        $inputFilter->add(array(
            'name'      => 'checkPassword',
            'required'  => false
        ));

        $checkPassword = $this->getCheckPassword();

        if (is_null($checkPassword) or $checkPassword == '1') {
            $inputFilter->add(array(
                'name' => 'password2',
                'validators' => array(
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'password'
                        ),
                    ),
                )
            ));
        } else {
            $inputFilter
                 ->add(array(
                     'name'      => 'password',
                     'required'  => false
                 ))
                 ->add(array(
                     'name'      => 'password2',
                     'required'  => false
                 ));
        }

        return $inputFilter;
    }
}