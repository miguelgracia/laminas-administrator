<?php

namespace Application\Form;

use Application\Validator\Recaptcha;
use Zend\Filter\StringTrim;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\Db\RecordExists;
use Zend\Validator\EmailAddress;

class ContactFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $adapter;

    public function __construct($name, array $options, $adapter)
    {
        parent::__construct($name, $options);

        $this->adapter = $adapter;

        $this->add([
            'name' => 'name',
            'type' => Text::class,
            'attributes' => [
                'id' => 'name',
                'class' => 'form-control',
                'placeholder' => 'Nombre'
            ],
        ])->add([
            'name' => 'email',
            'type' => Text::class,
            'attributes' => [
                'id' => 'email',
                'class' => 'form-control',
                'placeholder' => 'Email'
            ],
        ])->add([
            'name' => 'phone',
            'type' => Text::class,
            'attributes' => [
                'id' => 'phone',
                'class' => 'form-control',
                'placeholder' => 'Teléfono'
            ],
        ])->add([
            'name' => 'question_topic',
            'type' => Select::class,
            'attributes' => [
                'id' => 'question_topic',
                'class' => 'form-control'
            ],
            'allow_empty' => false,
            'options' => [
                'empty_option' => 'Temática de la pregunta',
                'value_options' => [
                    'repuestos' => 'Repuestos',
                    'ingenieria_electrica' => 'Ingeniería Eléctrica',
                    'reparaciones' => 'Reparaciones',
                    'ingenieria_mecanica' => 'Ingeniería mecánica',
                    'otros' => 'otros',
                ]
            ],
        ])->add([
            'name' => 'message',
            'type' => Text::class,
            'attributes' => [
                'id' => 'message',
                'class' => 'form-control',
                'rows' => '10',
                'placeholder' => 'Mensaje'
            ],
        ])->add([
            'name' => 'question_code',
            'type' => Text::class,
            'attributes' => [
                'id' => 'question_code',
                'class' => 'form-control',
                'placeholder' => 'Código de cliente (opcional)'
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
        ])->add([
            'name' => 'g-recaptcha-response',
            'type' => Hidden::class,
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'name' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class
                    ]
                ]
            ],

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
            'question_topic' => [
                'required' => true,
            ],
            'question_code' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => StringTrim::class
                    ]
                ],
                'validators' => [
                    [
                        'name' => RecordExists::class,
                        'options' => [
                            'table' => 'customers',
                            'field' => 'key',
                            'adapter' => $this->adapter
                        ]
                    ]
                ]
            ],
            'legal' => [
                'required' => true
            ],
            'g-recaptcha-response' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Recaptcha::class,
                        'options' => [
                            'captcha_secret' => $this->getOption('captcha_secret')
                        ]
                    ]
                ]
            ]
        ];
    }
}
