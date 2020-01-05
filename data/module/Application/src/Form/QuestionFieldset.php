<?php

namespace Application\Form;

use Zend\Filter\StringTrim;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Text;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\EmailAddress;

class QuestionFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name, array $options)
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'name',
            'type' => Text::class,
            'attributes' => [
                'id' => 'name',
                'class' => 'form-control'
            ],
        ])->add([
            'name' => 'email',
            'type' => Text::class,
            'attributes' => [
                'id' => 'email',
                'class' => 'form-control'
            ],
        ])->add([
            'name' => 'phone',
            'type' => Text::class,
            'attributes' => [
                'id' => 'phone',
                'class' => 'form-control'
            ],
        ])->add([
            'name' => 'message',
            'type' => Text::class,
            'attributes' => [
                'id' => 'message',
                'class' => 'form-control',
                'rows' => '10'
            ],
        ])->add([
            'name' => 'legal',
            'type' => Checkbox::class,
            'attributes' => [
                'id' => 'legal'
            ],
            'options' => [
                'use_hidden_element' => false
            ]
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'email' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class
                    ]
                ],
                'validators' => [
                    [
                        'name' => EmailAddress::class
                    ]
                ]
            ],
            'message' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class
                    ],
                ]
            ],
            'legal' => [
                'required' => true
            ]
        ];
    }
}
